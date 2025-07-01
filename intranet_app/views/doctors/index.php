<?php $pageTitle = 'Administrar Médicos'; ?>

<div class="p-1 pt-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Médicos</h2>
        <?php if (Auth::hasRole('admin')): ?>
        <button onclick="openDoctorModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 text-xs rounded-lg shadow-md transition-colors duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Nuevo Médico
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
       
        <form id="filterDoctorsForm" method="GET" action="<?php echo $intranetBaseUrl; ?>/medicos" class="space-y-4 md:space-y-0 md:grid md:grid-cols-6 md:gap-2">
        <h3 class="text-md font-bold text-gray-800 mb-2">Filtros</h3>    
        <div>
                <label for="filterName" class="block text-xs font-medium text-gray-700">Nombre:</label>
                <input type="text" id="filterName" name="name" value="<?php echo htmlspecialchars($filters['name'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Buscar por nombre">
            </div>
            <div>
                <label for="filterSpecialty" class="block text-xs font-medium text-gray-700">Especialidad:</label>
                <input type="text" id="filterSpecialty" name="specialty" value="<?php echo htmlspecialchars($filters['specialty'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Buscar por especialidad">
            </div>
            <div>
                <label for="filterIsActive" class="block text-xs font-medium text-gray-700">Estado:</label>
                <select id="filterIsActive" name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" <?php echo ($filters['is_active'] === 'all' ? 'selected' : ''); ?>>Todos</option>
                    <option value="1" <?php echo ($filters['is_active'] === '1' ? 'selected' : ''); ?>>Activo</option>
                    <option value="0" <?php echo ($filters['is_active'] === '0' ? 'selected' : ''); ?>>Inactivo</option>
                </select>
            </div>
           
                <button type="submit" class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Aplicar
                </button>
      
       
                <button type="button" onclick="clearDoctorFilters()" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-4 rounded-lg transition-colors duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.75M3 12v6m0-6h6"/></svg>
                    Limpiar
                </button>
         
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md px-6 py-2 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Listado de Médicos</h3>
        <div class="overflow-x-auto max-h-80">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Especialidad
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teléfono
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            % Honorarios
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
                    <?php if (!empty($doctors)): ?>
                        <?php foreach ($doctors as $doctor): ?>
                            <tr>
                                <td class="px-6 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                    <?php echo htmlspecialchars($doctor['name']); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo htmlspecialchars($doctor['specialty']); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo htmlspecialchars($doctor['phone'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo htmlspecialchars($doctor['email'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-800">
                                    <?php echo number_format($doctor['fee_percentage'], 2); ?>%
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-xs">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo $doctor['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $doctor['is_active'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-center text-xs font-medium">
                                    <?php if (Auth::hasRole('admin')): ?>
                                    <button onclick="openDoctorModal(<?php echo $doctor['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                    </button>
                                    <form action="<?php echo $intranetBaseUrl; ?>/medicos/toggle-status/<?php echo $doctor['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres cambiar el estado de este médico?');">
                                        <button type="submit" class="
                                            <?php echo $doctor['is_active'] ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'; ?>">
                                            <?php if ($doctor['is_active']): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-minus"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-2 whitespace-nowrap text-xs text-gray-500 text-center">No hay médicos registrados que coincidan con los filtros.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Añadir/Editar Médico -->
<div id="doctorModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="doctorModalTitle">Nuevo Médico</h3>
            <button onclick="toggleModal('doctorModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form id="doctorForm" action="<?php echo $intranetBaseUrl; ?>/medicos/create" method="POST" class="space-y-4">
            <input type="hidden" id="doctorId" name="doctor_id" value="">
            <div>
                <label for="doctorName" class="block text-xs font-medium text-gray-700">Nombre Completo:</label>
                <input type="text" id="doctorName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="doctorSpecialty" class="block text-xs font-medium text-gray-700">Especialidad:</label>
                <input type="text" id="doctorSpecialty" name="specialty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="doctorPhone" class="block text-xs font-medium text-gray-700">Teléfono:</label>
                <input type="text" id="doctorPhone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="doctorEmail" class="block text-xs font-medium text-gray-700">Email:</label>
                <input type="email" id="doctorEmail" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="doctorFeePercentage" class="block text-xs font-medium text-gray-700">Porcentaje de Honorarios (%):</label>
                <input type="number" step="0.01" id="doctorFeePercentage" name="fee_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required min="0" max="100">
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="doctorIsActive" name="is_active" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="doctorIsActive" class="ml-2 block text-xs text-gray-900">Activo</label>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('doctorModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Médico
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

    function openDoctorModal(doctorId = null) {
        const doctorModalTitle = document.getElementById('doctorModalTitle');
        const doctorForm = document.getElementById('doctorForm');
        const doctorIdInput = document.getElementById('doctorId');
        const doctorNameInput = document.getElementById('doctorName');
        const doctorSpecialtyInput = document.getElementById('doctorSpecialty');
        const doctorPhoneInput = document.getElementById('doctorPhone');
        const doctorEmailInput = document.getElementById('doctorEmail');
        const doctorFeePercentageInput = document.getElementById('doctorFeePercentage');
        const doctorIsActiveCheckbox = document.getElementById('doctorIsActive');

        doctorForm.reset();
        doctorIsActiveCheckbox.checked = true;
        doctorIdInput.value = '';
        doctorForm.action = `${intranetBaseUrl}/medicos/create`;
        doctorModalTitle.textContent = 'Nuevo Médico';

        if (doctorId) {
            doctorModalTitle.textContent = 'Editar Médico';
            doctorForm.action = `${intranetBaseUrl}/medicos/update/${doctorId}`;
            doctorIdInput.value = doctorId;

            fetch(`${intranetBaseUrl}/api/doctors/details/${doctorId}`)
                .then(response => response.json())
                .then(doctor => {
                    if (doctor) {
                        doctorNameInput.value = doctor.name;
                        doctorSpecialtyInput.value = doctor.specialty;
                        doctorPhoneInput.value = doctor.phone || '';
                        doctorEmailInput.value = doctor.email || '';
                        doctorFeePercentageInput.value = parseFloat(doctor.fee_percentage).toFixed(2);
                        doctorIsActiveCheckbox.checked = doctor.is_active == 1;
                    } else {
                        alert('Médico no encontrado.');
                        toggleModal('doctorModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching doctor details:', error);
                    alert('Error al cargar los detalles del médico.');
                    toggleModal('doctorModal', false);
                });
        }
        toggleModal('doctorModal');
    }

    // Función para limpiar los filtros de médicos
    window.clearDoctorFilters = function() {
        document.getElementById('filterName').value = '';
        document.getElementById('filterSpecialty').value = '';
        document.getElementById('filterIsActive').value = 'all';
        document.getElementById('filterDoctorsForm').submit(); // Envía el formulario para aplicar filtros vacíos
    };
</script>
