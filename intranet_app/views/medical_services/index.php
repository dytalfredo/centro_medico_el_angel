<?php $pageTitle = 'Administrar Servicios'; ?>

<div class="p-1 pt-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Servicios Médicos</h2>
        <?php if (Auth::hasRole('admin')): ?>
        <button onclick="openServiceModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 text-xs rounded-lg shadow-md transition-colors duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Nuevo Servicio
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
        
        <form id="filterServicesForm" method="GET" action="<?php echo $intranetBaseUrl; ?>/servicios" class="space-y-4 md:space-y-0 md:grid md:grid-cols-6 md:gap-2">
        <h3 class="text-md font-bold text-gray-800 mb-2">Filtros</h3>    
        <div>
                <label for="filterName" class="block text-xs font-medium text-gray-700">Nombre:</label>
                <input type="text" id="filterName" name="name" value="<?php echo htmlspecialchars($filters['name'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Buscar por nombre">
            </div>
            <div>
                <label for="filterMinPrice" class="block text-xs font-medium text-gray-700">Precio Mínimo ($):</label>
                <input type="number" step="0.01" id="filterMinPrice" name="min_price" value="<?php echo htmlspecialchars($filters['min_price'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0">
            </div>
            <div>
                <label for="filterMaxPrice" class="block text-xs font-medium text-gray-700">Precio Máximo ($):</label>
                <input type="number" step="0.01" id="filterMaxPrice" name="max_price" value="<?php echo htmlspecialchars($filters['max_price'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0">
            </div>
     
         
                <button type="submit" class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Aplicar
                </button>
           
        
                <button type="button" onclick="clearServiceFilters()" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-4 rounded-lg transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.75M3 12v6m0-6h6"/></svg>
                    Limpiar
                </button>
         
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-2 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Listado de Servicios</h3>
        <div class="overflow-x-auto max-h-80">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Precio Base ($)
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Descripción
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
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td class="px-6 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                    <?php echo htmlspecialchars($service['name']); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    $<?php echo number_format($service['base_price'], 2); ?>
                                </td>
                                <td class="px-6 py-2 text-xs text-gray-800 max-w-xs truncate">
                                    <?php echo htmlspecialchars($service['description'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo $service['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $service['is_active'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-center text-xs font-medium">
                                    <?php if (Auth::hasRole('admin')): ?>
                                    <button onclick="openServiceModal(<?php echo $service['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                    </button>
                                    <form action="<?php echo $intranetBaseUrl; ?>/servicios/toggle-status/<?php echo $service['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres cambiar el estado de este servicio?');">
                                        <button type="submit" class="
                                            <?php echo $service['is_active'] ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'; ?>">
                                            <?php if ($service['is_active']): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-x"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-check"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-2 whitespace-nowrap text-xs text-gray-500 text-center">No hay servicios registrados que coincidan con los filtros.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Añadir/Editar Servicio -->
<div id="serviceModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="serviceModalTitle">Nuevo Servicio</h3>
            <button onclick="toggleModal('serviceModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form id="serviceForm" action="<?php echo $intranetBaseUrl; ?>/servicios/create" method="POST" class="space-y-4">
            <input type="hidden" id="serviceId" name="service_id" value="">
            <div>
                <label for="serviceName" class="block text-xs font-medium text-gray-700">Nombre del Servicio:</label>
                <input type="text" id="serviceName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="serviceBasePrice" class="block text-xs font-medium text-gray-700">Precio Base ($):</label>
                <input type="number" step="0.01" id="serviceBasePrice" name="base_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required min="0">
            </div>
            <div>
                <label for="serviceDescription" class="block text-xs font-medium text-gray-700">Descripción (Opcional):</label>
                <textarea id="serviceDescription" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="serviceIsActive" name="is_active" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="serviceIsActive" class="ml-2 block text-xs text-gray-900">Activo</label>
            </div>

            <!-- Sección para Doctores Asociados -->
            <div class="border rounded-md p-4">
                <h4 class="text-md font-semibold text-gray-800 mb-3">Doctores Asociados</h4>
                <div id="associatedDoctorsCheckboxes" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    <?php if (!empty($allDoctors)): ?>
                        <?php foreach ($allDoctors as $doctor): ?>
                            <div class="flex items-center">
                                <input type="checkbox" id="doctor_<?php echo htmlspecialchars($doctor['id']); ?>" name="associated_doctors[]" value="<?php echo htmlspecialchars($doctor['id']); ?>" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="doctor_<?php echo htmlspecialchars($doctor['id']); ?>" class="ml-2 text-xs text-gray-900">
                                    <?php echo htmlspecialchars($doctor['name']); ?> (<?php echo htmlspecialchars($doctor['specialty']); ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-xs text-gray-500">No hay doctores disponibles para asociar.</p>
                    <?php endif; ?>
                </div>
                <input type="hidden" id="associatedDoctorsInput" name="associated_doctors">
            </div>


            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('serviceModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Servicio
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const intranetBaseUrl = '<?php echo $intranetBaseUrl; ?>';

    window.toggleModal = function(modalId, show = true) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    };

    function openServiceModal(serviceId = null) {
        const serviceModalTitle = document.getElementById('serviceModalTitle');
        const serviceForm = document.getElementById('serviceForm');
        const serviceIdInput = document.getElementById('serviceId');
        const serviceNameInput = document.getElementById('serviceName');
        const serviceBasePriceInput = document.getElementById('serviceBasePrice');
        const serviceDescriptionInput = document.getElementById('serviceDescription');
        const serviceIsActiveCheckbox = document.getElementById('serviceIsActive');
        const associatedDoctorsCheckboxesDiv = document.getElementById('associatedDoctorsCheckboxes');
        const associatedDoctorsInput = document.getElementById('associatedDoctorsInput');

        serviceForm.reset();
        serviceIsActiveCheckbox.checked = true;
        serviceIdInput.value = '';
        serviceForm.action = `${intranetBaseUrl}/servicios/create`;
        serviceModalTitle.textContent = 'Nuevo Servicio';

        associatedDoctorsCheckboxesDiv.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        if (serviceId) {
            serviceModalTitle.textContent = 'Editar Servicio';
            serviceForm.action = `${intranetBaseUrl}/servicios/update/${serviceId}`;
            serviceIdInput.value = serviceId;

            fetch(`${intranetBaseUrl}/api/servicios/details/${serviceId}`)
                .then(response => response.json())
                .then(service => {
                    if (service) {
                        serviceNameInput.value = service.name;
                        serviceBasePriceInput.value = parseFloat(service.base_price).toFixed(2);
                        serviceDescriptionInput.value = service.description || '';
                        serviceIsActiveCheckbox.checked = service.is_active == 1;

                        if (service.associated_doctors && Array.isArray(service.associated_doctors)) {
                            service.associated_doctors.forEach(doctorId => {
                                const checkbox = document.getElementById(`doctor_${doctorId}`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                }
                            });
                        }
                    } else {
                        alert('Servicio no encontrado.');
                        toggleModal('serviceModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching service details:', error);
                    alert('Error al cargar los detalles del servicio.');
                    toggleModal('serviceModal', false);
                });
        }
        toggleModal('serviceModal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const serviceForm = document.getElementById('serviceForm');
        const associatedDoctorsCheckboxesDiv = document.getElementById('associatedDoctorsCheckboxes');
        const associatedDoctorsInput = document.getElementById('associatedDoctorsInput');

        serviceForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const selectedDoctorIds = [];
            associatedDoctorsCheckboxesDiv.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                selectedDoctorIds.push(parseInt(checkbox.value));
            });

            associatedDoctorsInput.value = JSON.stringify(selectedDoctorIds);

            serviceForm.submit();
        });
    });

    // Función para limpiar los filtros de servicios
    window.clearServiceFilters = function() {
        document.getElementById('filterName').value = '';
        document.getElementById('filterMinPrice').value = '';
        document.getElementById('filterMaxPrice').value = '';
        document.getElementById('filterIsActive').value = 'all';
        document.getElementById('filterServicesForm').submit(); // Envía el formulario para aplicar filtros vacíos
    };
</script>
