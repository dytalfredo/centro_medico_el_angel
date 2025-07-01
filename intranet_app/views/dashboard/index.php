<?php $pageTitle = 'Panel'; 
$intranetBaseUrl = '/elangel_medical_center/admin';
$requestUri = $_SERVER['REQUEST_URI'];
$currentPageSegment = basename(parse_url($requestUri, PHP_URL_PATH));
if ($currentPageSegment === 'admin' || $currentPageSegment === '') {
    $currentPageSegment = 'dashboard';
}
?>



<div class="p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Información Relevante</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Card de Facturas Pendientes -->
        <a href="<?php echo $intranetBaseUrl; ?>/facturas" class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between border hover:border-8 border-blue-200 transition-all duration-200">   
        
        
        <div>
                <p class="text-sm font-medium text-gray-500">Facturas Pendientes</p>
                <p class="text-3xl font-bold text-blue-600 mt-1"><?php echo htmlspecialchars($invoices_pending_count); ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            </div>
 
        </a>

        <!-- Card de Médicos Activos -->
        <a href="<?php echo $intranetBaseUrl; ?>/medicos" class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between border hover:border-8 border-green-200 transition-all duration-200">
       
            <div>
                <p class="text-sm font-medium text-gray-500">Médicos Activos</p>
                <p class="text-3xl font-bold text-green-600 mt-1"><?php echo htmlspecialchars($active_doctors_count); ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
            </div>
       
        </a>

        <!-- Card de Servicios Ofrecidos -->
        <a href="<?php echo $intranetBaseUrl; ?>/servicios" class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between border hover:border-8 border-amber-200 transition-all duration-200">
        
            <div>
                <p class="text-sm font-medium text-gray-500">Servicios Ofrecidos</p>
                <p class="text-3xl font-bold text-amber-600 mt-1"><?php echo htmlspecialchars($active_services_count); ?></p>
            </div>
            <div class="bg-amber-100 p-3 rounded-full text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-boxes"><path d="M2.97 10.1c1.2-.6 2.7-.9 4.3-.9 1.6 0 3.1.3 4.3.9"/><path d="M2.97 19.1c1.2-.6 2.7-.9 4.3-.9 1.6 0 3.1.3 4.3.9"/><path d="M12.97 10.1c1.2-.6 2.7-.9 4.3-.9 1.6 0 3.1.3 4.3.9"/><path d="M12.97 19.1c1.2-.6 2.7-.9 4.3-.9 1.6 0 3.1.3 4.3.9"/><path d="M7.3 3.6c1.8-.8 3.7-1.2 5.7-1.2 2 0 3.9.4 5.7 1.2"/><path d="M7.3 12.6c1.8-.8 3.7-1.2 5.7-1.2 2 0 3.9.4 5.7 1.2"/><path d="M7.3 21.6c1.8-.8 3.7-1.2 5.7-1.2 2 0 3.9.4 5.7 1.2"/><path d="M2 10v9"/><path d="M12 10v9"/><path d="M22 10v9"/><path d="M7 21.5V10.5"/><path d="M17 21.5V10.5"/><path d="M7 3.5V12.5"/><path d="M17 3.5V12.5"/></svg>
            </div>

</a>

        
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 max-h-80">
        <h3 class="text-xl font-bold text-gray-800 mb-4 ">Últimas Actividades</h3>
        <div class="overflow-x-auto max-h-40">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha/Hora
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acción
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detalles
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($latest_logs)): ?>
                        <?php foreach ($latest_logs as $log): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($log['timestamp']))); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars($log['username'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $log['action']))); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <?php echo htmlspecialchars($log['details']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?php echo htmlspecialchars($log['ip_address'] ?? 'N/A'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay registros de auditoría recientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
