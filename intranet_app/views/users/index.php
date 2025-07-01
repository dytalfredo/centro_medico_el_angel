<?php $pageTitle = 'Administrar Usuarios'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Usuarios del Sistema</h2>
        <?php if (Auth::hasRole('admin')): ?>
        <button onclick="openNewUserModal()" class="bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus mr-2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Nuevo Usuario
        </button>
        <?php endif; ?>
    </div>

    <?php if (isset($flash_message) && $flash_message): ?>
        <div class="<?php echo $flash_message['type'] === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($flash_message['message']); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Lista de Usuarios</h3>
        <div class="overflow-x-auto max-h-80">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre Completo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Creado
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($u['id']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars($u['username']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars($u['full_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars($u['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo ($u['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'); ?>">
                                        <?php echo htmlspecialchars(ucfirst($u['role'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars(date('d/m/Y', strtotime($u['created_at']))); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick="openEditUserModal(<?php echo $u['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                    </button>
                                    <?php if (Auth::hasRole('admin') && $u['id'] !== $user['id']): // No permitir que un admin se elimine a sí mismo ?>
                                    <form action="<?php echo $intranetBaseUrl; ?>/usuarios/delete/<?php echo $u['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este usuario? Esta acción es irreversible.');">
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
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Usuario -->
<div id="userModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="userModalTitle">Nuevo Usuario</h3>
            <button onclick="toggleModal('userModal', false)" class="text-gray-500 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form id="userForm" action="<?php echo $intranetBaseUrl; ?>/usuarios/create" method="POST" class="space-y-4">
            <input type="hidden" id="userId" name="user_id" value="">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-900">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div id="passwordField">
                <label for="password" class="block text-sm font-medium text-gray-900">Contraseña:</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1" id="passwordHint">Dejar en blanco para no cambiar la contraseña.</p>
            </div>
            <div>
                <label for="fullName" class="block text-sm font-medium text-gray-900">Nombre Completo:</label>
                <input type="text" id="fullName" name="full_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-900">Email:</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-900">Rol:</label>
                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="assistant">Asistente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('userModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

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

    const intranetBaseUrl = '<?php echo $intranetBaseUrl; ?>'; // URL base desde PHP

    // Función para abrir el modal para crear un nuevo usuario
    window.openNewUserModal = function() {
        document.getElementById('userModalTitle').textContent = 'Nuevo Usuario';
        document.getElementById('userForm').action = `${intranetBaseUrl}/usuarios/create`;
        document.getElementById('userForm').reset(); // Limpiar el formulario
        document.getElementById('userId').value = ''; // Asegurarse de que no haya ID de usuario
        
        // Para "Nuevo Usuario", la contraseña es obligatoria
        document.getElementById('passwordField').classList.remove('hidden');
        document.getElementById('password').required = true;
        document.getElementById('passwordHint').textContent = 'Se requiere una contraseña para nuevos usuarios.';

        toggleModal('userModal', true);
    };

    // Función para abrir el modal para editar un usuario existente
    window.openEditUserModal = async function(userId) {
        document.getElementById('userModalTitle').textContent = 'Editar Usuario';
        document.getElementById('userForm').action = `${intranetBaseUrl}/usuarios/update/${userId}`;
        document.getElementById('userId').value = userId;

        // Para "Editar Usuario", la contraseña es opcional
        document.getElementById('passwordField').classList.remove('hidden'); // Asegurarse de que sea visible
        document.getElementById('password').required = false; // No obligatoria
        document.getElementById('passwordHint').textContent = 'Dejar en blanco para no cambiar la contraseña.';


        try {
            const response = await fetch(`${intranetBaseUrl}/api/usuarios/${userId}`);
            const result = await response.json();

            if (result.success && result.user) {
                const user = result.user;
                document.getElementById('username').value = user.username;
                document.getElementById('fullName').value = user.full_name;
                document.getElementById('email').value = user.email;
                document.getElementById('role').value = user.role;
                document.getElementById('password').value = ''; // Limpiar el campo de contraseña por seguridad
                
                // Si el usuario actual está editando su propia cuenta y es admin, no permitir cambiar su rol
                // O si el usuario es asistente, no puede editar su rol.
                const loggedInUserId = <?php echo json_encode(Auth::user()['id']); ?>;
                const loggedInUserRole = '<?php echo Auth::user()['role']; ?>';

                if (loggedInUserId == userId && loggedInUserRole === 'admin') {
                    document.getElementById('role').disabled = true; // Un admin no puede cambiar su propio rol
                    document.getElementById('role').title = 'No puedes cambiar tu propio rol de administrador.';
                } else if (loggedInUserRole === 'assistant') {
                     document.getElementById('role').disabled = true; // Un asistente no puede cambiar ningún rol
                     document.getElementById('role').title = 'Los asistentes no pueden cambiar roles de usuario.';
                } else {
                    document.getElementById('role').disabled = false;
                    document.getElementById('role').title = '';
                }

                toggleModal('userModal', true);
            } else {
                console.error('Error al cargar datos del usuario:', result.message);
                // Aquí podrías mostrar un mensaje de error más amigable al usuario
            }
        } catch (error) {
            console.error('Error de red al cargar datos del usuario:', error);
            // Aquí podrías mostrar un mensaje de error más amigable al usuario
        }
    };
</script>
