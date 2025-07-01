<?php $pageTitle = 'Reportes'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Reportes</h2>
        <?php if (Auth::hasRole('admin')): ?>
        <button onclick="toggleModal('generateReportModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            Generar Reporte PDF
        </button>
        <?php endif; ?>
    </div>

    <?php if (isset($flash_message) && $flash_message): ?>
        <div class="<?php echo $flash_message['type'] === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($flash_message['message']); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Ventas del Mes -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Ventas Mensuales (Año <?php echo htmlspecialchars($currentYear); ?>)</h3>
            <div class="relative h-80">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Honorarios Pagados del Mes -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Honorarios Pagados a Médicos (Año <?php echo htmlspecialchars($currentYear); ?>)</h3>
            <div class="relative h-80">
                <canvas id="monthlyPaidFeesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Generar Reporte PDF -->
<div id="generateReportModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Generar Reporte PDF</h3>
            <button onclick="toggleModal('generateReportModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form id="pdfReportForm" action="<?php echo $intranetBaseUrl; ?>/reportes/generar-pdf" method="POST" class="space-y-4">
            <div>
                <label for="reportType" class="block text-sm font-medium text-gray-700">Tipo de Reporte:</label>
                <select id="reportType" name="report_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Selecciona un tipo de reporte</option>
                    <option value="ventas_mensuales">Ventas Mensuales</option>
                    <option value="honorarios_medicos">Honorarios Médicos Pagados (Anual)</option>
                    <option value="honorarios_diarios_medicos">Honorarios Médicos a Pagar (Rango)</option>
                    <option value="facturas_por_paciente">Facturas por Paciente</option>
                    <!-- Agrega más tipos de reportes según sea necesario -->
                </select>
            </div>
            <div id="dateRangeFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Inicio:</label>
                    <input type="date" id="startDate" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha de Fin:</label>
                    <input type="date" id="endDate" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('generateReportModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Generar PDF
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Asegurarse de que toggleModal esté en el ámbito global
    window.toggleModal = window.toggleModal || function(modalId, show = true) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Datos pasados desde PHP
        const monthlySalesData = <?php echo $monthlySalesData; ?>;
        const monthlyPaidFeesData = <?php echo $monthlyPaidFeesData; ?>;

        // Gráfico de Ventas Mensuales
        const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Ventas ($)',
                    data: monthlySalesData,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', // Tailwind blue-500
                    borderColor: 'rgba(37, 99, 235, 1)', // Tailwind blue-700
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto Total ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mes'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Honorarios Pagados a Médicos
        const feesCtx = document.getElementById('monthlyPaidFeesChart').getContext('2d');
        new Chart(feesCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Honorarios Pagados ($)',
                    data: monthlyPaidFeesData,
                    backgroundColor: 'rgba(168, 85, 247, 0.6)', // Tailwind purple-500
                    borderColor: 'rgba(126, 34, 206, 1)', // Tailwind purple-700
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto Pagado ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mes'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                }
            }
        });

        // Lógica para mostrar/ocultar campos de fecha en el modal de reportes
        const reportTypeSelect = document.getElementById('reportType');
        const dateRangeFields = document.getElementById('dateRangeFields');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        function toggleDateFields() {
            if (reportTypeSelect.value === 'honorarios_diarios_medicos' || reportTypeSelect.value === 'facturas_por_paciente') {
                dateRangeFields.classList.remove('hidden');
                // Establecer fecha actual como por defecto para el reporte diario
                const today = new Date().toISOString().split('T')[0];
                startDateInput.value = today;
                endDateInput.value = today;
            } else {
                dateRangeFields.classList.add('hidden');
                startDateInput.value = '';
                endDateInput.value = '';
            }
        }

        reportTypeSelect.addEventListener('change', toggleDateFields);

        // Llamar en la carga inicial para establecer el estado correcto
        toggleDateFields();

        // Restablecer el formulario del modal de reporte al cerrarse
        const generateReportModal = document.getElementById('generateReportModal');
        generateReportModal.addEventListener('click', function(event) {
            if (event.target === generateReportModal) { // Solo si se hace clic en el overlay
                document.getElementById('pdfReportForm').reset();
                toggleDateFields(); // Reiniciar visibilidad de campos de fecha
            }
        });
    });
</script>
