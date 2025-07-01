<?php $pageTitle = 'Configuración General'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Configuración del Sistema</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Configuración de la Base de Datos Principal</h3>
        
        <?php if (!empty($flash_message)): ?>
            <div class="mb-4 p-3 rounded-md text-sm
                <?php echo $flash_message['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <?php echo htmlspecialchars($flash_message['message']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo $intranetBaseUrl; ?>/configuracion/update" method="POST" class="space-y-4">
            <div>
                <label for="dbHost" class="block text-sm font-medium text-gray-700">Host de la Base de Datos:</label>
                <input type="text" id="dbHost" name="db_host" value="<?php echo htmlspecialchars($dbConfig['DB_HOST'] ?? ''); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="dbUser" class="block text-sm font-medium text-gray-700">Usuario de la Base de Datos:</label>
                <input type="text" id="dbUser" name="db_user" value="<?php echo htmlspecialchars($dbConfig['DB_USER'] ?? ''); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="dbPass" class="block text-sm font-medium text-gray-700">Contraseña de la Base de Datos:</label>
                <input type="password" id="dbPass" name="db_pass" value="<?php echo htmlspecialchars($dbConfig['DB_PASS'] ?? ''); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-sm text-gray-500">Deja en blanco si no hay cambios en la contraseña o si está vacía.</p>
            </div>
            <div>
                <label for="dbName" class="block text-sm font-medium text-gray-700">Nombre de la Base de Datos:</label>
                <input type="text" id="dbName" name="db_name" value="<?php echo htmlspecialchars($dbConfig['DB_NAME'] ?? ''); ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
