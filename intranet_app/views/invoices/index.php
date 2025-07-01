<?php $pageTitle = 'Gestión de Facturas'; ?>

<div class="p-1 pt-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Facturas</h2>
        <?php if (Auth::hasRole('admin') || Auth::hasRole('assistant')): ?>
        <button onclick="toggleModal('invoiceModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 text-xs rounded-lg shadow-md transition-colors duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Nueva Factura
        </button>
        <?php endif; ?>
    </div>

    <?php if (isset($flash_message) && $flash_message): ?>
        <div class="<?php echo $flash_message['type'] === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($flash_message['message']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Sección de Filtros -->
    <div class="bg-white rounded-xl shadow-md py-2 px-6 border border-gray-200 mb-6">
        
        <form id="filterForm" method="GET" action="<?php echo $intranetBaseUrl; ?>/facturas" class="space-y-4 md:space-y-0 md:grid md:grid-cols-7 md:gap-2">
        <h3 class="text-md font-bold text-gray-800 mb-2">Filtros</h3>    
        <div>
                <label for="filterStatus" class="block text-xs font-medium text-gray-700">Estado:</label>
                <select id="filterStatus" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" <?php echo ($filters['status'] === 'all' ? 'selected' : ''); ?>>Todos</option>
                    <option value="pending" <?php echo ($filters['status'] === 'pending' ? 'selected' : ''); ?>>Pendiente</option>
                    <option value="pagada" <?php echo ($filters['status'] === 'pagada' ? 'selected' : ''); ?>>Pagada</option>
                    <option value="cancelled" <?php echo ($filters['status'] === 'cancelled' ? 'selected' : ''); ?>>Cancelada</option>
                </select>
            </div>
            <div class="relative">
                <label for="filterPatientSearch" class="block text-xs font-medium text-gray-700">Paciente:</label>
                <input type="text" id="filterPatientSearch" name="patient_name_display" value="<?php echo htmlspecialchars($filters['patient_name_display'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Buscar paciente">
                <input type="hidden" id="filterPatientId" name="patient_id" value="<?php echo htmlspecialchars($filters['patient_id'] ?? ''); ?>">
                <div id="filterPatientSearchResults" class="absolute z-10 bg-white border border-gray-300 w-full rounded-md shadow-lg max-h-40 overflow-y-auto mt-1 hidden"></div>
            </div>
            <div>
                <label for="filterStartDate" class="block text-xs font-medium text-gray-700">Fecha Desde:</label>
                <input type="date" id="filterStartDate" name="start_date" value="<?php echo htmlspecialchars($filters['start_date'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="filterEndDate" class="block text-xs font-medium text-gray-700">Fecha Hasta:</label>
                <input type="date" id="filterEndDate" name="end_date" value="<?php echo htmlspecialchars($filters['end_date'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class=" text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Aplicar Filtros
                </button>
                <button type="button" onclick="clearFilters()" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-4 rounded-lg transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.75M3 12v6m0-6h6"/></svg>
                    Limpiar Filtros
                </button>
            <div class="md:col-span-4 flex space-x-3 mt-1">
                
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-2 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Listado de Facturas</h3>
        <div class="overflow-x-auto max-h-80">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nº Factura
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paciente
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Médico Principal (1er Ítem)
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Monto Total
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($latestInvoices)): ?>
                        <?php foreach ($latestInvoices as $invoice): ?>
                            <tr>
                                <td class="px-6 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                    <?php echo htmlspecialchars($invoice['invoice_number']); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo htmlspecialchars($invoice['patient_name']); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo htmlspecialchars($invoice['doctor_name'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo htmlspecialchars(date('d/m/Y', strtotime($invoice['invoice_date']))); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php
                                        $displayAmount = $invoice['total_amount'];
                                        $displaySymbol = '$';
                                        if (in_array($invoice['payment_method'], ['bolivares_pago_movil', 'bs_efectivo'])) {
                                            $displayAmount = $invoice['total_amount'] * $invoice['exchange_rate'];
                                            $displaySymbol = 'BVC';
                                        }
                                        echo $displaySymbol . number_format($displayAmount, 2);
                                    ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php
                                        if ($invoice['status'] === 'pagada') echo 'bg-green-100 text-green-800';
                                        elseif ($invoice['status'] === 'pending') echo 'bg-yellow-100 text-yellow-800';
                                        else echo 'bg-red-100 text-red-800';
                                        ?>">
                                        <?php echo htmlspecialchars(ucfirst($invoice['status'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-center text-xs font-medium">
                                    <button onclick="openEditInvoiceModal(<?php echo $invoice['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                    </button>
                                    <?php if (Auth::hasRole('admin')): ?>
                                    <form action="<?php echo $intranetBaseUrl; ?>/facturas/delete/<?php echo $invoice['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta factura?');">
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-2 whitespace-nowrap text-xs text-gray-500 text-center">No hay facturas registradas que coincidan con los filtros.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Factura (Crear/Editar) -->
<div id="invoiceModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="invoiceModalTitle">Nueva Factura</h3>
            <button onclick="toggleModal('invoiceModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form id="invoiceForm" action="<?php echo $intranetBaseUrl; ?>/facturas/create" method="POST" class="space-y-4">
            <input type="hidden" id="invoiceId" name="invoice_id" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="patientSearch" class="block text-xs font-medium text-gray-700">Paciente:</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="patientSearch" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Buscar paciente por nombre o cédula" required>
                        <button type="button" onclick="toggleModal('newPatientModal')" class="mt-1 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-3 rounded-md shadow-sm transition-colors duration-200 text-xs whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus inline-block mr-1"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                            Registrar Nuevo
                        </button>
                    </div>
                    <input type="hidden" id="patientId" name="patient_id">
                    <div id="patientSearchResults" class="absolute z-10 bg-white border border-gray-300 w-fit rounded-md shadow-lg max-h-60 overflow-y-auto mt-1 hidden"></div>
                    <p id="selectedPatientName" class="text-xs text-gray-800 mt-1 block font-semibold"></p>
                    <p id="selectedPatientIDNumber" class="text-xs text-gray-600 mt-1 block"></p>
                    <p id="selectedPatientPhone" class="text-xs text-gray-600 mt-1 block"></p>
                    <p id="selectedPatientAddress" class="text-xs text-gray-600 mt-1 block"></p>
                </div>
                <div>
                    <label for="invoiceDate" class="block text-xs font-medium text-gray-700">Fecha de Emisión:</label>
                    <input type="date" id="invoiceDate" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="paymentMethod" class="block text-xs font-medium text-gray-700">Forma de Pago:</label>
                    <select id="paymentMethod" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Selecciona forma de pago...</option>
                        <option value="bolivares_pago_movil">Bolívares Pago Móvil</option>
                        <option value="bs_efectivo">Bolívares Efectivo</option>
                        <option value="dolares_efectivo">Dólares Efectivo</option>
                        <option value="dolares_zelle">Dólares Zelle</option>
                    </select>
                </div>
                 <!-- Nuevo campo para la tasa de cambio -->
                <div id="exchangeRateField" class="hidden">
                    <label for="exchangeRate" class="block text-xs font-medium text-gray-700">Tasa de Cambio (BS/USD):</label>
                    <div class="flex items-center">
                        <input type="number" step="0.01" id="exchangeRate" name="exchange_rate" value="1.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            <?php echo Auth::hasRole('assistant') ? 'readonly' : ''; ?>>
                        <?php if (Auth::hasRole('admin')): ?>
                        <button type="button" id="setExchangeRateButton" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded-md shadow-sm transition-colors duration-200 text-xs">
                            Establecer
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php if (Auth::hasRole('assistant')): ?>
                        <p class="text-xs text-gray-500 mt-1">Solo los administradores pueden cambiar la tasa de cambio.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="border rounded-md p-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">Ítems/Servicios</h4>
                <div id="invoiceItemsContainer" class="space-y-3">
                    <!-- Los ítems se añadirán aquí dinámicamente con JavaScript -->
                </div>
                <button type="button" onclick="addInvoiceItem()" class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Agregar Ítem
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700">Estado:</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500
                        <?php echo Auth::hasRole('assistant') ? 'bg-gray-100 cursor-not-allowed' : ''; ?>"
                        <?php echo Auth::hasRole('assistant') ? 'disabled' : ''; ?>
                    >
                        <option value="pending">Pendiente</option>
                        <?php if (Auth::hasRole('admin')): ?>
                            <option value="pagada">Pagada</option>
                            <option value="cancelled">Cancelada</option>
                        <?php endif; ?>
                    </select>
                    <?php if (Auth::hasRole('assistant')): ?>
                        <p class="text-xs text-gray-500 mt-1">Solo los administradores pueden cambiar el estado a Pagada o Cancelada.</p>
                    <?php endif; ?>
                </div>
                <div class="text-right">
                    <span class="text-lg font-medium text-gray-700">Total:</span>
                    <span id="totalAmountDisplay" class="text-2xl font-bold text-blue-600 ml-2">$0.00</span>
                    <input type="hidden" id="totalAmount" name="total_amount">
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-medium text-gray-700">Notas (Opcional):</label>
                <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <input type="hidden" id="invoiceItemsJson" name="invoice_items_json">

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('invoiceModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Factura
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Nuevo Modal para Registrar Paciente -->
<div id="newPatientModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Registrar Nuevo Paciente</h3>
            <button onclick="toggleModal('newPatientModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form id="newPatientForm" class="space-y-4">
            <div>
                <label for="patientName" class="block text-xs font-medium text-gray-700">Nombre Completo:</label>
                <input type="text" id="patientName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="patientIDNumber" class="block text-xs font-medium text-gray-700">Cédula/Identificación (V/J-XXXXXXXX):</label>
                <input type="text" id="patientIDNumber" name="id_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: V-12345678 o J-12345678" required>
            </div>
            <div>
                <label for="patientPhone" class="block text-xs font-medium text-gray-700">Teléfono (Ej: (0414)-1234567):</label>
                <input type="text" id="patientPhone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: (0414)-1234567" required>
            </div>
            <div>
                <label for="patientEmail" class="block text-xs font-medium text-gray-700">Email (Opcional):</label>
                <input type="email" id="patientEmail" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="patientAddress" class="block text-xs font-medium text-gray-700">Dirección (Opcional):</label>
                <textarea id="patientAddress" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label for="patientDateOfBirth" class="block text-xs font-medium text-gray-700">Fecha de Nacimiento (Opcional):</label>
                <input type="date" id="patientDateOfBirth" name="date_of_birth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('newPatientModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Registrar Paciente
                </button>
            </div>
             <div id="patientRegisterMessage" class="mt-4 text-center text-xs font-semibold"></div>
        </form>
    </div>
</div>

<script>
    // Global toggleModal function (available before DOMContentLoaded)
    window.toggleModal = function(modalId, show = true) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
                // Special handling for newPatientModal on close
                if (modalId === 'newPatientModal') {
                    const newPatientForm = document.getElementById('newPatientForm');
                    const patientRegisterMessage = document.getElementById('patientRegisterMessage');
                    if (newPatientForm) newPatientForm.reset(); // Limpiar el formulario del paciente al cerrar
                    if (patientRegisterMessage) patientRegisterMessage.textContent = ''; // Limpiar mensajes
                }
            }
        }
    };


    // Variables globales para el formulario de factura
    let invoiceItems = [];
    let allMedicalServices = []; // Almacenará TODOS los servicios médicos activos
    const intranetBaseUrl = '<?php echo $intranetBaseUrl; ?>'; // URL base desde PHP

    document.addEventListener('DOMContentLoaded', function() {
        const patientSearchInput = document.getElementById('patientSearch');
        const patientSearchResults = document.getElementById('patientSearchResults');
        const patientIdInput = document.getElementById('patientId');
        const selectedPatientNameSpan = document.getElementById('selectedPatientName');
        const selectedPatientIDNumberSpan = document.getElementById('selectedPatientIDNumber'); // Nuevo
        const selectedPatientPhoneSpan = document.getElementById('selectedPatientPhone');     // Nuevo
        const selectedPatientAddressSpan = document.getElementById('selectedPatientAddress'); // Nuevo

        const invoiceItemsContainer = document.getElementById('invoiceItemsContainer');
        const totalAmountDisplay = document.getElementById('totalAmountDisplay');
        const totalAmountInput = document.getElementById('totalAmount');
        const invoiceForm = document.getElementById('invoiceForm');
        const invoiceModalTitle = document.getElementById('invoiceModalTitle');
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const exchangeRateField = document.getElementById('exchangeRateField');
        const exchangeRateInput = document.getElementById('exchangeRate');
        const setExchangeRateButton = document.getElementById('setExchangeRateButton'); // Solo para admins

        // Nuevos elementos para el modal de paciente
        const newPatientForm = document.getElementById('newPatientForm');
        const patientRegisterMessage = document.getElementById('patientRegisterMessage');
        const patientIDNumberInput = document.getElementById('patientIDNumber'); // Input de cédula/RIF en modal de registro
        const patientPhoneInput = document.getElementById('patientPhone'); // Input de teléfono en modal de registro

        // Elementos de filtro
        const filterPatientSearchInput = document.getElementById('filterPatientSearch');
        const filterPatientIdInput = document.getElementById('filterPatientId');
        const filterPatientSearchResults = document.getElementById('filterPatientSearchResults');
        const filterStatusSelect = document.getElementById('filterStatus');
        const filterStartDateInput = document.getElementById('filterStartDate');
        const filterEndDateInput = document.getElementById('filterEndDate');
        const filterForm = document.getElementById('filterForm');


        // Fetch ALL medical services once on page load
        fetch(`${intranetBaseUrl}/api/services/details?service_id=all`)
            .then(response => response.json())
            .then(data => {
                allMedicalServices = data;
                renderInvoiceItems(); // Renderiza ítems iniciales o existentes (para edición)
            })
            .catch(error => console.error('Error fetching all medical services:', error));


        // Función para agregar un nuevo ítem de servicio a la factura
        window.addInvoiceItem = function(item = null) {
            const itemIndex = invoiceItems.length;
            // Si es un ítem nuevo, inicializar con valores predeterminados.
            invoiceItems.push(item || { service_id: '', doctor_id: '', quantity: 1, price_at_invoice: 0, subtotal: 0 });

            // Referencia al ítem que acabamos de añadir o que se está editando
            const currentItem = invoiceItems[itemIndex];

            const itemDiv = document.createElement('div');
            itemDiv.classList.add('flex', 'flex-wrap', 'items-center', 'space-x-2', 'mb-2', 'invoice-item-row', 'border', 'border-gray-200', 'p-2', 'rounded-md');
            itemDiv.innerHTML = `
                <div class="flex-1 min-w-[200px] mb-2 md:mb-0">
                    <label class="block text-xs font-medium text-gray-500">Servicio:</label>
                    <select id="serviceSelect_${itemIndex}" data-index="${itemIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 service-select" required>
                        <option value="">Seleccionar un servicio</option>
                        ${allMedicalServices.map(service => `
                            <option value="${service.id}" data-price="${service.base_price}">
                                ${service.name} ($${parseFloat(service.base_price).toFixed(2)})
                            </option>
                        `).join('')}
                    </select>
                </div>
                <div class="flex-1 min-w-[200px] mb-2 md:mb-0">
                    <label class="block text-xs font-medium text-gray-500">Doctor:</label>
                    <select id="doctorSelect_${itemIndex}" data-index="${itemIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 doctor-select" required>
                        <option value="">Seleccionar doctor</option>
                        <!-- Opciones de doctores se cargarán dinámicamente -->
                    </select>
                </div>
                <div class="w-20 mb-2 md:mb-0">
                    <label class="block text-xs font-medium text-gray-500">Cant:</label>
                    <input type="number" data-index="${itemIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-center quantity-input" value="${currentItem.quantity}" min="1" required>
                </div>
                <div class="w-24 mb-2 md:mb-0">
                    <label class="block text-xs font-medium text-gray-500">P. Unit:</label>
                    <input type="text" data-index="${itemIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm price-input bg-gray-50" value="$${currentItem.price_at_invoice.toFixed(2)}" readonly>
                </div>
                <div class="w-28 mb-2 md:mb-0">
                    <label class="block text-xs font-medium text-gray-500">Subtotal:</label>
                    <input type="text" data-index="${itemIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm subtotal-input bg-gray-50" value="$${currentItem.subtotal.toFixed(2)}" readonly>
                </div>
                <div class="flex items-center justify-center h-full pt-4 md:pt-0">
                    <button type="button" onclick="removeInvoiceItem(${itemIndex})" class="text-red-600 hover:text-red-800 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                </div>
            `;
            invoiceItemsContainer.appendChild(itemDiv);

            const serviceSelect = document.getElementById(`serviceSelect_${itemIndex}`);
            const doctorSelect = document.getElementById(`doctorSelect_${itemIndex}`);
            const priceInput = itemDiv.querySelector('.price-input');
            const qtyInput = itemDiv.querySelector('.quantity-input');

            // Set selected service if item is being loaded (for editing)
            if (currentItem && currentItem.service_id) {
                serviceSelect.value = currentItem.service_id;
                // Trigger change to load doctors for this service and set doctor
                loadDoctorsForService(serviceSelect.value, doctorSelect, currentItem.doctor_id);
            }
            updateItemDisplayCurrency(itemIndex); // Llama para actualizar la visualización inicial de moneda

            // Event listener para el cambio de servicio
            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const serviceId = this.value;

                invoiceItems[itemIndex].service_id = parseInt(serviceId);
                invoiceItems[itemIndex].price_at_invoice = basePrice; // Siempre guardar precio base en USD
                updateItemDisplayCurrency(itemIndex); // Actualiza la visualización de moneda
                updateItemSubtotal(itemIndex);

                // Cargar doctores para el servicio seleccionado
                loadDoctorsForService(serviceId, doctorSelect);
            });

            // Event listener para el cambio de doctor en el ítem
            doctorSelect.addEventListener('change', function() {
                invoiceItems[itemIndex].doctor_id = parseInt(this.value);
            });

            // Event listener para la cantidad
            qtyInput.addEventListener('input', function() {
                invoiceItems[itemIndex].quantity = parseInt(this.value) || 0;
                updateItemSubtotal(itemIndex);
            });
        };

        // Función para cargar doctores para un servicio específico
        async function loadDoctorsForService(serviceId, doctorSelectElement, selectedDoctorId = null) {
            doctorSelectElement.innerHTML = '<option value="">Cargando doctores...</option>';
            doctorSelectElement.disabled = true;

            if (!serviceId) {
                doctorSelectElement.innerHTML = '<option value="">Selecciona un servicio primero</option>';
                invoiceItems[parseInt(doctorSelectElement.dataset.index)].doctor_id = ''; // Limpiar doctor_id en el modelo
                return;
            }

            try {
                const response = await fetch(`${intranetBaseUrl}/api/doctors/for-service?service_id=${serviceId}`);
                const doctors = await response.json();

                doctorSelectElement.innerHTML = '<option value="">Seleccionar doctor</option>';
                if (doctors.length > 0) {
                    doctors.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = `${doctor.name} (${doctor.specialty})`;
                        doctorSelectElement.appendChild(option);
                    });

                    if (selectedDoctorId) {
                        doctorSelectElement.value = selectedDoctorId;
                        invoiceItems[parseInt(doctorSelectElement.dataset.index)].doctor_id = selectedDoctorId;
                    } else if (doctors.length === 1) {
                        doctorSelectElement.value = doctors[0].id; // Seleccionar automáticamente si solo hay uno
                        invoiceItems[parseInt(doctorSelectElement.dataset.index)].doctor_id = doctors[0].id;
                    } else {
                        invoiceItems[parseInt(doctorSelectElement.dataset.index)].doctor_id = ''; // Limpiar si no hay selección automática
                    }
                } else {
                    doctorSelectElement.innerHTML = '<option value="">No hay doctores para este servicio</option>';
                    invoiceItems[parseInt(doctorSelectElement.dataset.index)].doctor_id = '';
                }
            } catch (error) {
                console.error('Error fetching doctors for service:', error);
                doctorSelectElement.innerHTML = '<option value="">Error al cargar doctores</option>';
                invoiceItems[parseInt(doctorSelectElement.dataset.index)].doctor_id = '';
            } finally {
                doctorSelectElement.disabled = false;
            }
        }

        window.removeInvoiceItem = function(index) {
            invoiceItems.splice(index, 1);
            renderInvoiceItems(); // Vuelve a renderizar todos los ítems para actualizar índices
            calculateTotal();
        };

        // Función para actualizar la visualización de moneda de un ítem
        function updateItemDisplayCurrency(index) {
            const item = invoiceItems[index];
            const priceInput = document.querySelector(`.invoice-item-row:nth-child(${index + 1}) .price-input`);
            const subtotalInput = document.querySelector(`.invoice-item-row:nth-child(${index + 1}) .subtotal-input`);

            if (!priceInput || !subtotalInput) return; // Asegurarse de que los elementos existan

            const selectedPaymentMethod = paymentMethodSelect.value;
            const currentExchangeRate = parseFloat(exchangeRateInput.value) || 1.00;

            let displayPrice = item.price_at_invoice;
            let displaySubtotal = item.subtotal;
            let displaySymbol = '$';

            if (selectedPaymentMethod === 'bolivares_pago_movil' || selectedPaymentMethod === 'bs_efectivo') {
                displayPrice = item.price_at_invoice * currentExchangeRate;
                displaySubtotal = item.subtotal * currentExchangeRate;
                displaySymbol = 'BVC';
            }

            priceInput.value = `${displaySymbol}${displayPrice.toFixed(2)}`;
            subtotalInput.value = `${displaySymbol}${displaySubtotal.toFixed(2)}`;
        }


        function updateItemSubtotal(index) {
            const item = invoiceItems[index];
            item.subtotal = item.quantity * item.price_at_invoice; // Subtotal en USD
            updateItemDisplayCurrency(index); // Actualizar solo la visualización
            calculateTotal();
        }

        function calculateTotal() {
            let totalUSD = invoiceItems.reduce((sum, item) => sum + item.subtotal, 0); // Total siempre en USD
            let displayTotal = totalUSD;
            let displaySymbol = '$';

            const selectedPaymentMethod = paymentMethodSelect.value;
            const currentExchangeRate = parseFloat(exchangeRateInput.value) || 1.00;

            if (selectedPaymentMethod === 'bolivares_pago_movil' || selectedPaymentMethod === 'bs_efectivo') {
                displayTotal = totalUSD * currentExchangeRate;
                displaySymbol = 'BVC';
                exchangeRateField.classList.remove('hidden');
            } else {
                exchangeRateField.classList.add('hidden');
            }

            totalAmountDisplay.textContent = `${displaySymbol}${displayTotal.toFixed(2)}`;
            totalAmountInput.value = totalUSD.toFixed(2); // Guardar siempre el total en USD en el input oculto
        }

        function renderInvoiceItems() {
            invoiceItemsContainer.innerHTML = ''; // Limpia el contenedor
            invoiceItems.forEach((item, index) => {
                addInvoiceItem(item); // Reusa la función addInvoiceItem para renderizar
            });
            calculateTotal(); // Recalcula el total después de renderizar
        }


        // Autocomplete de paciente para el MODAL DE FACTURA
        let patientSearchTimeout;
        patientSearchInput.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const query = this.value;
            if (query.length < 3) {
                patientSearchResults.innerHTML = '';
                patientSearchResults.classList.add('hidden');
                // Limpiar campos de detalle de paciente si la búsqueda es muy corta
                patientIdInput.value = '';
                selectedPatientNameSpan.textContent = '';
                selectedPatientIDNumberSpan.textContent = '';
                selectedPatientPhoneSpan.textContent = '';
                selectedPatientAddressSpan.textContent = '';
                return;
            }

            patientSearchTimeout = setTimeout(function() {
                fetch(`${intranetBaseUrl}/api/patients/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        patientSearchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(patient => {
                                const div = document.createElement('div');
                                div.classList.add('p-2', 'cursor-pointer', 'hover:bg-blue-100', 'rounded-md');
                                div.textContent = `${patient.name} (${patient.id_number})`;
                                div.onclick = () => {
                                    patientIdInput.value = patient.id;
                                    patientSearchInput.value = `${patient.name} (${patient.id_number})`;
                                    selectedPatientNameSpan.textContent = `Paciente: ${patient.name}`;
                                    selectedPatientIDNumberSpan.textContent = `Cédula/RIF: ${patient.id_number}`;
                                    selectedPatientPhoneSpan.textContent = `Teléfono: ${patient.phone}`;
                                    selectedPatientAddressSpan.textContent = `Dirección: ${patient.address || 'N/A'}`;
                                    patientSearchResults.classList.add('hidden');
                                };
                                patientSearchResults.appendChild(div);
                            });
                            patientSearchResults.classList.remove('hidden');
                        } else {
                            patientSearchResults.classList.add('hidden');
                            // Limpiar campos de detalle de paciente si no hay resultados
                            patientIdInput.value = '';
                            selectedPatientNameSpan.textContent = 'Paciente no encontrado.';
                            selectedPatientIDNumberSpan.textContent = '';
                            selectedPatientPhoneSpan.textContent = '';
                            selectedPatientAddressSpan.textContent = '';
                        }
                    })
                    .catch(error => console.error('Error fetching patients:', error));
            }, 300);
        });

        // Ocultar resultados de búsqueda de paciente (modal de factura) al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!patientSearchInput.contains(event.target) && !patientSearchResults.contains(event.target)) {
                patientSearchResults.classList.add('hidden');
            }
        });

        // Event listener para cambios en el método de pago
        paymentMethodSelect.addEventListener('change', function() {
            calculateTotal(); // Re-calcula el total para actualizar la moneda
            // Re-renderizar todos los ítems para que sus precios unitarios y subtotales se actualicen con la nueva moneda
            invoiceItems.forEach((item, index) => {
                updateItemDisplayCurrency(index);
            });
        });

        // Event listener para cambios en la tasa de cambio (solo para admins)
        // El botón "Establecer" ahora es opcional para admins, el input cambia directamente
        // Si no es admin, el input será readonly, por lo que este listener no se ejecutará
        exchangeRateInput.addEventListener('input', function() {
             calculateTotal(); // Re-calcula el total para actualizar la moneda
             // Re-renderizar todos los ítems para que sus precios unitarios y subtotales se actualicen con la nueva moneda
            invoiceItems.forEach((item, index) => {
                updateItemDisplayCurrency(index);
            });
        });

        <?php if (Auth::hasRole('admin')): ?>
        if (setExchangeRateButton) { // Asegurarse de que el botón existe
            setExchangeRateButton.addEventListener('click', function() {
                // No se necesita lógica aquí ya que el 'input' listener ya hace la actualización
                // Esto es solo para que el botón "Establecer" tenga un feedback visual si se desea.
                alert('Tasa de cambio establecida para esta factura.');
            });
        }
        <?php endif; ?>


        // Función para abrir el modal en modo edición
        window.openEditInvoiceModal = function(invoiceId) {
            invoiceModalTitle.textContent = 'Editar Factura';
            invoiceForm.action = `${intranetBaseUrl}/facturas/update/${invoiceId}`;
            document.getElementById('invoiceId').value = invoiceId;

            // Limpiar ítems y total (se cargarán desde el fetch)
            invoiceItems = [];
            invoiceItemsContainer.innerHTML = ''; // Limpia el contenedor directamente
            calculateTotal(); // Restablece el total y la visibilidad de la tasa

            // Fetch invoice data
            fetch(`${intranetBaseUrl}/api/facturas/${invoiceId}`)
                .then(response => response.json())
                .then(invoice => {
                    if (invoice) {
                        // Populate main form fields
                        document.getElementById('patientSearch').value = `${invoice.patient_name} (${invoice.patient_id_number || 'N/A'})`;
                        document.getElementById('patientId').value = invoice.patient_id;
                        selectedPatientNameSpan.textContent = `Paciente: ${invoice.patient_name}`;
                        selectedPatientIDNumberSpan.textContent = `Cédula/RIF: ${invoice.id_number || 'N/A'}`; // Corrected: invoice.id_number
                        selectedPatientPhoneSpan.textContent = `Teléfono: ${invoice.phone || 'N/A'}`;
                        selectedPatientAddressSpan.textContent = `Dirección: ${invoice.address || 'N/A'}`;


                        document.getElementById('invoiceDate').value = invoice.invoice_date;
                        document.getElementById('paymentMethod').value = invoice.payment_method || '';
                        document.getElementById('notes').value = invoice.notes || '';
                        document.getElementById('status').value = invoice.status;

                        // Set exchange rate
                        exchangeRateInput.value = parseFloat(invoice.exchange_rate).toFixed(2);
                        // Mostrar/Ocultar el campo de tasa según el método de pago cargado
                        if (['bolivares_pago_movil', 'bs_efectivo'].includes(invoice.payment_method)) {
                            exchangeRateField.classList.remove('hidden');
                        } else {
                            exchangeRateField.classList.add('hidden');
                        }


                        // Check permissions for status field
                        const statusField = document.getElementById('status');
                        // Use === '1' for PHP boolean values converted to string
                        if ('<?php echo Auth::hasRole('assistant'); ?>' === '1' && invoice.status !== 'pending') {
                            statusField.disabled = true;
                            statusField.classList.add('bg-gray-100', 'cursor-not-allowed');
                        } else {
                            statusField.disabled = false;
                            statusField.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        }

                        // Load invoice items
                        return fetch(`${intranetBaseUrl}/api/facturas/${invoiceId}/items`);
                    }
                })
                .then(response => response.json())
                .then(items => {
                    invoiceItems = items.map(item => ({
                        service_id: item.service_id,
                        doctor_id: item.doctor_id, // Incluir doctor_id del ítem
                        quantity: parseInt(item.quantity),
                        price_at_invoice: parseFloat(item.price_at_invoice), // Precio base en USD
                        subtotal: parseFloat(item.subtotal) // Subtotal en USD
                    }));
                    renderInvoiceItems(); // Esto poblará también los select de doctores y actualizará la visualización de moneda
                    calculateTotal(); // Recalcula el total con la tasa y método de pago cargados
                })
                .catch(error => console.error('Error fetching invoice items:', error));

            toggleModal('invoiceModal');
        };

        // Event listener para el envío del formulario de factura
        invoiceForm.addEventListener('submit', function(event) {
            // Asegúrate de que los ítems se envíen como JSON
            document.getElementById('invoiceItemsJson').value = JSON.stringify(invoiceItems);
            // Si el campo de estado está deshabilitado para el asistente, asegúrate de que se envíe el valor correcto (o el valor forzado a 'pending')
             const statusField = document.getElementById('status');
             if (statusField.disabled) {
                 statusField.removeAttribute('disabled'); // Habilita temporalmente para que se envíe el valor
             }
        });

        // Cuando el modal se abre por primera vez (o al crear una nueva factura), asegurar que los campos estén limpios
        document.querySelector('[onclick="toggleModal(\'invoiceModal\')"]').addEventListener('click', function() {
            invoiceModalTitle.textContent = 'Nueva Factura';
            invoiceForm.action = `${intranetBaseUrl}/facturas/create`;
            invoiceForm.reset(); // Limpia todos los campos del formulario
            document.getElementById('invoiceId').value = '';
            invoiceItems = []; // Reinicia los ítems
            // allMedicalServices ya está cargado por el fetch inicial, no se reinicia
            renderInvoiceItems(); // Limpia los ítems visualmente
            
            // Restablece la tasa de cambio a 1.00 por defecto y oculta el campo
            exchangeRateInput.value = '1.00';
            exchangeRateField.classList.add('hidden'); // Ocultar por defecto para nueva factura

            calculateTotal(); // Reinicia el total (mostrará $0.00)
            selectedPatientNameSpan.textContent = ''; // Limpia el nombre del paciente seleccionado
            selectedPatientIDNumberSpan.textContent = '';
            selectedPatientPhoneSpan.textContent = '';
            selectedPatientAddressSpan.textContent = '';

            document.getElementById('status').disabled = false; // Habilita el estado para nueva factura
            document.getElementById('status').classList.remove('bg-gray-100', 'cursor-not-allowed');
            document.getElementById('status').value = 'pending'; // Estado por defecto
            paymentMethodSelect.value = ''; // Limpia la forma de pago
        });

        // --- Lógica para el nuevo modal de registro de paciente ---
        
        // No es necesario redefinir toggleModal aquí, ya está en el ámbito global.

        // Formato para Cédula/RIF
        patientIDNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^VJ0-9]/g, ''); // Solo V, J y números
            let formattedValue = '';

            if (value.length > 0) {
                // Si empieza con V o J, lo mantenemos. Si no, asumimos V por defecto.
                if (value.startsWith('V') || value.startsWith('J')) {
                    formattedValue = value[0];
                    value = value.substring(1); // Remover el prefijo para procesar solo los números
                } else {
                    formattedValue = 'V'; // Por defecto si no se especifica
                }

                // Añadir guion después del prefijo si hay números
                if (value.length > 0) {
                    formattedValue += '-' + value.replace(/\D/g, '').substring(0, 8); // Máximo 8 dígitos
                }
            }
            e.target.value = formattedValue;
        });

        // Formato para Teléfono
        patientPhoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Eliminar todo lo que no sea dígito
            let formattedValue = '';

            if (value.length > 0) {
                formattedValue = '(';
                if (value.length > 0) {
                    formattedValue += value.substring(0, 4); // Primeros 4 dígitos
                }
                if (value.length >= 4) {
                    formattedValue += ')-';
                    formattedValue += value.substring(4, 11); // Siguientes 7 dígitos
                }
            }
            e.target.value = formattedValue;
        });


        newPatientForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evitar el envío de formulario tradicional

            const formData = new FormData(newPatientForm);
            const patientData = {};
            for (let [key, value] of formData.entries()) {
                patientData[key] = value;
            }

            patientRegisterMessage.textContent = 'Registrando paciente...';
            patientRegisterMessage.classList.remove('text-green-700', 'text-red-700');
            patientRegisterMessage.classList.add('text-gray-600');


            try {
                const response = await fetch(`${intranetBaseUrl}/api/patients/create`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(patientData)
                });
                const result = await response.json();

                if (result.success) {
                    patientRegisterMessage.textContent = result.message;
                    patientRegisterMessage.classList.remove('text-gray-600', 'text-red-700');
                    patientRegisterMessage.classList.add('text-green-700');
                    
                    // Si el paciente se registró exitosamente, seleccionarlo en el modal de factura
                    if (result.patient) {
                        patientIdInput.value = result.patient.id;
                        patientSearchInput.value = `${result.patient.name} (${result.patient.id_number})`;
                        selectedPatientNameSpan.textContent = `Paciente: ${result.patient.name}`;
                        selectedPatientIDNumberSpan.textContent = `Cédula/RIF: ${result.patient.id_number || 'N/A'}`;
                        selectedPatientPhoneSpan.textContent = `Teléfono: ${result.patient.phone || 'N/A'}`;
                        selectedPatientAddressSpan.textContent = `Dirección: ${result.patient.address || 'N/A'}`;
                        // Cerrar el modal de nuevo paciente
                        toggleModal('newPatientModal', false);
                    }
                } else {
                    patientRegisterMessage.textContent = result.message;
                    patientRegisterMessage.classList.remove('text-gray-600', 'text-green-700');
                    patientRegisterMessage.classList.add('text-red-700');
                }
            } catch (error) {
                console.error('Error al registrar paciente:', error);
                patientRegisterMessage.textContent = 'Error de red o servidor al registrar paciente.';
                patientRegisterMessage.classList.remove('text-gray-600', 'text-green-700');
                patientRegisterMessage.classList.add('text-red-700');
            }
        });

        // --- Lógica de Filtro ---

        let filterPatientSearchTimeout;
        filterPatientSearchInput.addEventListener('input', function() {
            clearTimeout(filterPatientSearchTimeout);
            const query = this.value;
            if (query.length < 3) {
                filterPatientSearchResults.innerHTML = '';
                filterPatientSearchResults.classList.add('hidden');
                filterPatientIdInput.value = ''; // Clear hidden ID if search is too short
                return;
            }

            filterPatientSearchTimeout = setTimeout(function() {
                fetch(`${intranetBaseUrl}/api/patients/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        filterPatientSearchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(patient => {
                                const div = document.createElement('div');
                                div.classList.add('p-2', 'cursor-pointer', 'hover:bg-blue-100', 'rounded-md');
                                div.textContent = `${patient.name} (${patient.id_number})`;
                                div.onclick = () => {
                                    filterPatientIdInput.value = patient.id;
                                    filterPatientSearchInput.value = `${patient.name} (${patient.id_number})`;
                                    filterPatientSearchResults.classList.add('hidden');
                                };
                                filterPatientSearchResults.appendChild(div);
                            });
                            filterPatientSearchResults.classList.remove('hidden');
                        } else {
                            filterPatientSearchResults.classList.add('hidden');
                            filterPatientIdInput.value = '';
                        }
                    })
                    .catch(error => console.error('Error fetching patients for filter:', error));
            }, 300);
        });

        // Ocultar resultados de búsqueda de paciente (filtro) al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!filterPatientSearchInput.contains(event.target) && !filterPatientSearchResults.contains(event.target)) {
                filterPatientSearchResults.classList.add('hidden');
            }
        });

        window.clearFilters = function() {
            filterStatusSelect.value = 'all';
            filterPatientSearchInput.value = '';
            filterPatientIdInput.value = '';
            filterStartDateInput.value = '';
            filterEndDateInput.value = '';
            filterForm.submit(); // Submit the form to clear filters
        };
    });
</script>
