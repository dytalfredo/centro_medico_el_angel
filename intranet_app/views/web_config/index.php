<?php $pageTitle = 'Configuración de Contenido Web'; ?>

<div class="p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Contenido Dinámico de la Web</h2>

    <?php if (!empty($flash_message)): ?>
        <div class="mb-4 p-3 rounded-md text-sm
            <?php echo $flash_message['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
            <?php echo htmlspecialchars($flash_message['message']); ?>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-md border border-gray-200 max-h-[400px]">
        
        <div class="md:w-1/4 lg:w-1/5 overflow-x-auto border-r border-gray-200 p-6 over">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Secciones</h3>
            <nav class="space-y-2">
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/settings" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'settings') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings mr-2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.78 1.22a2 2 0 0 0 .73 2.73l.09.09a2 2 0 0 1 0 2.83l-.08.15a2 2 0 0 0-.73 2.73l-1.22.78a2 2 0 0 0 2.73-.73l.09-.08a2 2 0 0 1 1-.43l.18-.43a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.78-1.22a2 2 0 0 0-.73-2.73l-.09-.09a2 2 0 0 1 0-2.83l.08-.15a2 2 0 0 0 .73-2.73l1.22-.78a2 2 0 0 0-2.73-.73l-.09.08a2 2 0 0 1-1-.43l-.18-.43a2 2 0 0 0-2-2v-.44a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    Ajustes Generales
                </a>
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/doctors" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'doctors') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope mr-2"><path d="M7 11v2a4 4 0 0 0 4 4h2a4 4 0 0 0 4-4v-2"/><path d="M11 17v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/><path d="M12 17v-5h3"/><path d="M18 13V9a2 2 0 0 0-2-2c-.9 0-1.7.5-2.2 1.2L9 11"/><path d="M22 8H16"/><path d="M15 2l.6 1.4c.2.4.5.8.9 1.1l.9.6"/><path d="M11 2l-.6 1.4a2.3 2.3 0 0 1-.9 1.1l-.9.6"/></svg>
                    Médicos
                </a>
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/faqs" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'faqs') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list mr-2"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 15h4"/><path d="M8 11h.01"/><path d="M8 15h.01"/></svg>
                    Preguntas Frecuentes (FAQs)
                </a>
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/pages" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'pages') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    Páginas de Contenido
                </a>
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/services" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'services') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-heart mr-2"><path d="M11 14H7a4 4 0 0 0-4 4v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2a4 4 0 0 0-4-4Z"/><path d="M18 9v.01"/><path d="M17 7v.01"/><path d="M14 10v.01"/><path d="M12 12v.01"/><path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v.01"/><path d="M13 2c-.3 0-.5.1-.8.2s-.7.3-1 .5c-.7.5-1.5 1.4-2 2.7l-.3.7c-.2.6-.4 1.2-.5 1.7H20c-.1-.4-.2-.8-.4-1.2L18.4 6.4c-.7-1.3-1.5-2.2-2.3-2.7-.4-.3-.8-.5-1.2-.7-.3-.1-.6-.2-1-.2Z"/></svg>
                    Servicios
                </a>
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/slider-images" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'slider-images') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/><circle cx="12" cy="12" r="3"/><path d="M22 12a10 10 0 1 1-14.7-8.7"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                    Imágenes del Slider
                </a>
                <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web/testimonials" class="flex items-center p-2 rounded-md transition-colors duration-200
                   <?php echo ($section === 'testimonials') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-quote mr-2"><path d="M21 14c0 4.7-3.3 7-9 7c-1.7 0-3.3-.4-4.8-1.1L2 22l1.1-4.8c-.7-1.5-1.1-3.2-1.1-4.8C2 7.3 5.3 5 12 5c5.7 0 9 2.3 9 7Z"/><path d="M8 10h.01"/><path d="M12 10h.01"/><path d="M16 10h.01"/></svg>
                    Testimonios
                </a>
            </nav>
        </div>

        <!-- Contenido Principal -->
        <div class="md:flex-1 p-6 contenido">
            <?php if ($section === 'settings'): ?>
                <!-- Sección para Ajustes Generales -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Ajustes Generales del Sitio Web</h3>
                <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/update-settings" method="POST" class="space-y-4">
                    <?php if (!empty($settings)): ?>
                        <?php foreach ($settings as $setting): ?>
                            <div>
                                <label for="setting_<?php echo htmlspecialchars($setting['id']); ?>" class="block text-sm font-medium text-gray-700">
                                    <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $setting['setting_key']))); ?>:
                                </label>
                                <?php if (strlen($setting['setting_value'] ?? '') > 50 || strpos($setting['setting_value'] ?? '', "\n") !== false || strpos($setting['setting_key'], 'iframe') !== false): ?>
                                    <textarea id="setting_<?php echo htmlspecialchars($setting['id']); ?>"
                                              name="setting_<?php echo htmlspecialchars($setting['id']); ?>"
                                              rows="<?php echo (strpos($setting['setting_key'], 'iframe') !== false) ? '5' : '3'; ?>"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($setting['setting_value'] ?? ''); ?></textarea>
                                <?php else: ?>
                                    <input type="text" id="setting_<?php echo htmlspecialchars($setting['id']); ?>"
                                           name="setting_<?php echo htmlspecialchars($setting['id']); ?>"
                                           value="<?php echo htmlspecialchars($setting['setting_value'] ?? ''); ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-600">No hay configuraciones disponibles para modificar.</p>
                    <?php endif; ?>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            <?php elseif ($section === 'doctors'): ?>
                <!-- Sección para Médicos del sitio web público -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Médicos del Sitio Web</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <button onclick="openWebDoctorModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Nuevo Médico (Web)
                </button>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($doctors)): ?>
                                <?php foreach ($doctors as $doctor): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($doctor['id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($doctor['name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <?php if (Auth::hasRole('admin')): ?>
                                            <button onclick="openWebDoctorModal(<?php echo $doctor['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                            </button>
                                            <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/doctors/delete/<?php echo $doctor['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este médico de la web?');">
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
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay médicos registrados en el sitio web.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'faqs'): ?>
                <!-- Sección para FAQs del sitio web público -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Preguntas Frecuentes (FAQs) del Sitio Web</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <button onclick="openWebFaqModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Nueva FAQ (Web)
                </button>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pregunta</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Respuesta</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($faqs)): ?>
                                <?php foreach ($faqs as $faq): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($faq['id']); ?></td>
                                        <td class="px-6 py-4 max-w-xs truncate text-sm text-gray-800"><?php echo htmlspecialchars($faq['question']); ?></td>
                                        <td class="px-6 py-4 max-w-sm truncate text-sm text-gray-800"><?php echo htmlspecialchars($faq['answer']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <?php if (Auth::hasRole('admin')): ?>
                                            <button onclick="openWebFaqModal(<?php echo $faq['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                            </button>
                                            <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/faqs/delete/<?php echo $faq['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta FAQ de la web?');">
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
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay FAQs registradas en el sitio web.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'pages'): ?>
                <!-- Sección para Páginas de Contenido del sitio web público -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Páginas de Contenido del Sitio Web</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <button onclick="openWebPageModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Nueva Página (Web)
                </button>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave de Página</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenido</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($pages)): ?>
                                <?php foreach ($pages as $page): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($page['id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($page['page_key']); ?></td>
                                        <td class="px-6 py-4 max-w-sm truncate text-sm text-gray-800"><?php echo htmlspecialchars($page['content']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <?php if (Auth::hasRole('admin')): ?>
                                            <button onclick="openWebPageModal(<?php echo $page['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                            </button>
                                            <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/pages/delete/<?php echo $page['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta página de la web?');">
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
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay páginas registradas en el sitio web.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'services'): ?>
                <!-- Sección para Servicios del sitio web público -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Servicios del Sitio Web</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <button onclick="openWebServiceModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Nuevo Servicio (Web)
                </button>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($services)): ?>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($service['id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($service['name']); ?></td>
                                        <td class="px-6 py-4 max-w-sm truncate text-sm text-gray-800"><?php echo htmlspecialchars($service['description'] ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <?php if (Auth::hasRole('admin')): ?>
                                            <button onclick="openWebServiceModal(<?php echo $service['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                            </button>
                                            <button onclick="openManageServiceDoctorsModal(<?php echo $service['id']; ?>, '<?php echo htmlspecialchars(addslashes($service['name'])); ?>')" class="text-purple-600 hover:text-purple-900 mr-3" title="Gestionar Doctores Asociados">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="7" r="4"/><path d="M22 21a8 8 0 0 0-16 0"/><circle cx="14" cy="7" r="4"/></svg>
                                            </button>
                                            <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/services/delete/<?php echo $service['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este servicio de la web? Esto también eliminará sus asociaciones con doctores.');">
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
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay servicios registrados en el sitio web.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'slider-images'): ?>
                <!-- Sección para Imágenes del Slider del sitio web público -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Imágenes del Slider del Sitio Web</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <button onclick="openWebSliderImageModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Nueva Imagen (Slider)
                </button>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL de Imagen</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($sliderImages)): ?>
                                <?php foreach ($sliderImages as $image): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($image['id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($image['title']); ?></td>
                                        <td class="px-6 py-4 max-w-xs truncate text-sm text-blue-600 hover:underline"><a href="<?php echo htmlspecialchars($image['image_url']); ?>" target="_blank"><?php echo htmlspecialchars($image['image_url']); ?></a></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($image['order_display']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <?php if (Auth::hasRole('admin')): ?>
                                            <button onclick="openWebSliderImageModal(<?php echo $image['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                            </button>
                                            <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/slider-images/delete/<?php echo $image['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta imagen del slider de la web?');">
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
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay imágenes en el slider del sitio web.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'testimonials'): ?>
                <!-- Sección para Testimonios del sitio web público -->
                <h3 class="text-xl font-bold text-gray-800 mb-4">Testimonios del Sitio Web</h3>
                <?php if (Auth::hasRole('admin')): ?>
                <button onclick="openWebTestimonialModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Nuevo Testimonio (Web)
                </button>
                <?php endif; ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cita</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Autor</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($testimonials)): ?>
                                <?php foreach ($testimonials as $testimonial): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($testimonial['id']); ?></td>
                                        <td class="px-6 py-4 max-w-sm truncate text-sm text-gray-800"><?php echo htmlspecialchars($testimonial['quote']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($testimonial['author']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <?php if (Auth::hasRole('admin')): ?>
                                            <button onclick="openWebTestimonialModal(<?php echo $testimonial['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="M15 5l4 4"/></svg>
                                            </button>
                                            <form action="<?php echo $intranetBaseUrl; ?>/configuracion-web/testimonials/delete/<?php echo $testimonial['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este testimonio de la web?');">
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
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay testimonios registrados en el sitio web.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals para las secciones de contenido web -->

<!-- Modal para Añadir/Editar Médico (Web) -->
<div id="webDoctorModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="webDoctorModalTitle">Nuevo Médico (Web)</h3>
            <button onclick="toggleModal('webDoctorModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="webDoctorForm" action="<?php echo $intranetBaseUrl; ?>/configuracion-web/doctors/create" method="POST" class="space-y-4">
            <input type="hidden" id="webDoctorId" name="id" value="">
            <div>
                <label for="webDoctorName" class="block text-sm font-medium text-gray-700">Nombre del Médico:</label>
                <input type="text" id="webDoctorName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('webDoctorModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Médico
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Añadir/Editar FAQ (Web) -->
<div id="webFaqModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="webFaqModalTitle">Nueva FAQ (Web)</h3>
            <button onclick="toggleModal('webFaqModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="webFaqForm" action="<?php echo $intranetBaseUrl; ?>/configuracion-web/faqs/create" method="POST" class="space-y-4">
            <input type="hidden" id="webFaqId" name="id" value="">
            <div>
                <label for="webFaqQuestion" class="block text-sm font-medium text-gray-700">Pregunta:</label>
                <textarea id="webFaqQuestion" name="question" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label for="webFaqAnswer" class="block text-sm font-medium text-gray-700">Respuesta:</label>
                <textarea id="webFaqAnswer" name="answer" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('webFaqModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar FAQ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Añadir/Editar Página (Web) -->
<div id="webPageModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="webPageModalTitle">Nueva Página (Web)</h3>
            <button onclick="toggleModal('webPageModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="webPageForm" action="<?php echo $intranetBaseUrl; ?>/configuracion-web/pages/create" method="POST" class="space-y-4">
            <input type="hidden" id="webPageId" name="id" value="">
            <div>
                <label for="webPageKey" class="block text-sm font-medium text-gray-700">Clave de Página:</label>
                <input type="text" id="webPageKey" name="page_key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="webPageContent" class="block text-sm font-medium text-gray-700">Contenido:</label>
                <textarea id="webPageContent" name="content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('webPageModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Página
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Añadir/Editar Servicio (Web) -->
<div id="webServiceModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="webServiceModalTitle">Nuevo Servicio (Web)</h3>
            <button onclick="toggleModal('webServiceModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="webServiceForm" action="<?php echo $intranetBaseUrl; ?>/configuracion-web/services/create" method="POST" class="space-y-4">
            <input type="hidden" id="webServiceId" name="id" value="">
            <div>
                <label for="webServiceName" class="block text-sm font-medium text-gray-700">Nombre del Servicio:</label>
                <input type="text" id="webServiceName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="webServiceDescription" class="block text-sm font-medium text-gray-700">Descripción:</label>
                <textarea id="webServiceDescription" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('webServiceModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Servicio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Gestionar Doctores Asociados a un Servicio (Web) -->
<div id="manageServiceDoctorsModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Gestionar Doctores para: <span id="serviceDoctorsServiceName" class="font-semibold text-blue-700"></span></h3>
            <button onclick="toggleModal('manageServiceDoctorsModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="manageServiceDoctorsForm" action="" method="POST" class="space-y-4">
            <input type="hidden" id="manageServiceDoctorsServiceId" name="service_id" value="">
            <div id="doctorCheckboxesContainer" class="space-y-2">
                <!-- Los checkboxes de doctores se cargarán aquí dinámicamente -->
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('manageServiceDoctorsModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Asociaciones
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Añadir/Editar Imagen de Slider (Web) -->
<div id="webSliderImageModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="webSliderImageModalTitle">Nueva Imagen de Slider (Web)</h3>
            <button onclick="toggleModal('webSliderImageModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="webSliderImageForm" action="<?php echo $intranetBaseUrl; ?>/configuracion-web/slider-images/create" method="POST" class="space-y-4">
            <input type="hidden" id="webSliderImageId" name="id" value="">
            <div>
                <label for="webSliderImageUrl" class="block text-sm font-medium text-gray-700">URL de la Imagen:</label>
                <input type="url" id="webSliderImageUrl" name="image_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="https://ejemplo.com/imagen.jpg">
            </div>
            <div>
                <label for="webSliderImageTitle" class="block text-sm font-medium text-gray-700">Título:</label>
                <input type="text" id="webSliderImageTitle" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="webSliderImageOrder" class="block text-sm font-medium text-gray-700">Orden de Visualización:</label>
                <input type="number" id="webSliderImageOrder" name="order_display" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required value="0">
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('webSliderImageModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Imagen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Añadir/Editar Testimonio (Web) -->
<div id="webTestimonialModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-2xl font-bold text-gray-800" id="webTestimonialModalTitle">Nuevo Testimonio (Web)</h3>
            <button onclick="toggleModal('webTestimonialModal', false)" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="webTestimonialForm" action="<?php echo $intranetBaseUrl; ?>/configuracion-web/testimonials/create" method="POST" class="space-y-4">
            <input type="hidden" id="webTestimonialId" name="id" value="">
            <div>
                <label for="webTestimonialQuote" class="block text-sm font-medium text-gray-700">Cita:</label>
                <textarea id="webTestimonialQuote" name="quote" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label for="webTestimonialAuthor" class="block text-sm font-medium text-gray-700">Autor:</label>
                <input type="text" id="webTestimonialAuthor" name="author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal('webTestimonialModal', false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                    Guardar Testimonio
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const intranetBaseUrl = '<?php echo $intranetBaseUrl; ?>'; // URL base desde PHP

    // Función global para abrir/cerrar modales
    window.toggleModal = function(modalId, show = true) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (show) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Evita el scroll del fondo
            } else {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden'); // Restaura el scroll del fondo
                // Limpiar formularios específicos al cerrar
                if (modalId === 'webDoctorModal') {
                    document.getElementById('webDoctorForm').reset();
                } else if (modalId === 'webFaqModal') {
                    document.getElementById('webFaqForm').reset();
                } else if (modalId === 'webPageModal') {
                    document.getElementById('webPageForm').reset();
                } else if (modalId === 'webServiceModal') {
                    document.getElementById('webServiceForm').reset();
                } else if (modalId === 'webSliderImageModal') {
                    document.getElementById('webSliderImageForm').reset();
                } else if (modalId === 'webTestimonialModal') {
                    document.getElementById('webTestimonialForm').reset();
                } else if (modalId === 'manageServiceDoctorsModal') {
                    document.getElementById('manageServiceDoctorsForm').reset();
                    document.getElementById('doctorCheckboxesContainer').innerHTML = ''; // Limpiar checkboxes
                }
            }
        }
    };

    // --- Funciones específicas para abrir modales ---

    // Doctor
    function openWebDoctorModal(doctorId = null) {
        const modalTitle = document.getElementById('webDoctorModalTitle');
        const form = document.getElementById('webDoctorForm');
        const idInput = document.getElementById('webDoctorId');
        const nameInput = document.getElementById('webDoctorName');

        form.reset(); // Limpiar el formulario
        idInput.value = '';
        form.action = `${intranetBaseUrl}/configuracion-web/doctors/create`;
        modalTitle.textContent = 'Nuevo Médico (Web)';

        if (doctorId) {
            modalTitle.textContent = 'Editar Médico (Web)';
            form.action = `${intranetBaseUrl}/configuracion-web/doctors/update/${doctorId}`;
            idInput.value = doctorId;

            fetch(`${intranetBaseUrl}/api/web-config/doctors/${doctorId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(doctor => {
                    if (doctor) {
                        nameInput.value = doctor.name;
                    } else {
                        alert('Médico no encontrado.');
                        toggleModal('webDoctorModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching doctor details:', error);
                    alert('Error al cargar los detalles del médico.');
                    toggleModal('webDoctorModal', false);
                });
        }
        toggleModal('webDoctorModal');
    }

    // FAQ
    function openWebFaqModal(faqId = null) {
        const modalTitle = document.getElementById('webFaqModalTitle');
        const form = document.getElementById('webFaqForm');
        const idInput = document.getElementById('webFaqId');
        const questionInput = document.getElementById('webFaqQuestion');
        const answerInput = document.getElementById('webFaqAnswer');

        form.reset();
        idInput.value = '';
        form.action = `${intranetBaseUrl}/configuracion-web/faqs/create`;
        modalTitle.textContent = 'Nueva FAQ (Web)';

        if (faqId) {
            modalTitle.textContent = 'Editar FAQ (Web)';
            form.action = `${intranetBaseUrl}/configuracion-web/faqs/update/${faqId}`;
            idInput.value = faqId;

            fetch(`${intranetBaseUrl}/api/web-config/faqs/${faqId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(faq => {
                    if (faq) {
                        questionInput.value = faq.question;
                        answerInput.value = faq.answer;
                    } else {
                        alert('FAQ no encontrada.');
                        toggleModal('webFaqModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching FAQ details:', error);
                    alert('Error al cargar los detalles de la FAQ.');
                    toggleModal('webFaqModal', false);
                });
        }
        toggleModal('webFaqModal');
    }

    // Page
    function openWebPageModal(pageId = null) {
        const modalTitle = document.getElementById('webPageModalTitle');
        const form = document.getElementById('webPageForm');
        const idInput = document.getElementById('webPageId');
        const keyInput = document.getElementById('webPageKey');
        const contentInput = document.getElementById('webPageContent');

        form.reset();
        idInput.value = '';
        keyInput.readOnly = false; // Permitir editar la clave para nuevos
        form.action = `${intranetBaseUrl}/configuracion-web/pages/create`;
        modalTitle.textContent = 'Nueva Página (Web)';

        if (pageId) {
            modalTitle.textContent = 'Editar Página (Web)';
            form.action = `${intranetBaseUrl}/configuracion-web/pages/update/${pageId}`;
            idInput.value = pageId;
            keyInput.readOnly = true; // No permitir editar la clave en edición

            fetch(`${intranetBaseUrl}/api/web-config/pages/${pageId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(page => {
                    if (page) {
                        keyInput.value = page.page_key;
                        contentInput.value = page.content;
                    } else {
                        alert('Página no encontrada.');
                        toggleModal('webPageModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching page details:', error);
                    alert('Error al cargar los detalles de la página.');
                    toggleModal('webPageModal', false);
                });
        }
        toggleModal('webPageModal');
    }

    // Service
    function openWebServiceModal(serviceId = null) {
        const modalTitle = document.getElementById('webServiceModalTitle');
        const form = document.getElementById('webServiceForm');
        const idInput = document.getElementById('webServiceId');
        const nameInput = document.getElementById('webServiceName');
        const descriptionInput = document.getElementById('webServiceDescription');

        form.reset();
        idInput.value = '';
        form.action = `${intranetBaseUrl}/configuracion-web/services/create`;
        modalTitle.textContent = 'Nuevo Servicio (Web)';

        if (serviceId) {
            modalTitle.textContent = 'Editar Servicio (Web)';
            form.action = `${intranetBaseUrl}/configuracion-web/services/update/${serviceId}`;
            idInput.value = serviceId;

            fetch(`${intranetBaseUrl}/api/web-config/services/${serviceId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(service => {
                    if (service) {
                        nameInput.value = service.name;
                        descriptionInput.value = service.description || ''; // Puede ser nulo
                    } else {
                        alert('Servicio no encontrado.');
                        toggleModal('webServiceModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching service details:', error);
                    alert('Error al cargar los detalles del servicio.');
                    toggleModal('webServiceModal', false);
                });
        }
        toggleModal('webServiceModal');
    }

    // Manage Service Doctors
    async function openManageServiceDoctorsModal(serviceId, serviceName) {
        const serviceNameSpan = document.getElementById('serviceDoctorsServiceName');
        const serviceIdInput = document.getElementById('manageServiceDoctorsServiceId');
        const doctorsContainer = document.getElementById('doctorCheckboxesContainer');
        const form = document.getElementById('manageServiceDoctorsForm');

        serviceNameSpan.textContent = serviceName;
        serviceIdInput.value = serviceId;
        form.action = `${intranetBaseUrl}/configuracion-web/services/manage-doctors/${serviceId}`;
        doctorsContainer.innerHTML = '<p class="text-gray-500">Cargando doctores...</p>'; // Mensaje de carga

        try {
            const allDoctorsResponse = await fetch(`${intranetBaseUrl}/api/web-config/doctors/all`); // Asumiendo que ya tienes una API para esto o que el controlador te los pasa
            if (!allDoctorsResponse.ok) throw new Error('Failed to fetch all doctors.');
            const allDoctors = await allDoctorsResponse.json();

            const associatedDoctorsResponse = await fetch(`${intranetBaseUrl}/api/web-config/service-doctors/${serviceId}`);
            if (!associatedDoctorsResponse.ok) throw new Error('Failed to fetch associated doctors.');
            const associatedDoctorIds = await associatedDoctorsResponse.json();

            doctorsContainer.innerHTML = ''; // Limpiar el mensaje de carga

            if (allDoctors.length === 0) {
                doctorsContainer.innerHTML = '<p class="text-gray-600">No hay doctores disponibles en el sitio web para asociar.</p>';
                toggleModal('manageServiceDoctorsModal', true);
                return;
            }

            allDoctors.forEach(doctor => {
                const isChecked = associatedDoctorIds.includes(doctor.id);
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'flex items-center';
                checkboxDiv.innerHTML = `
                    <input type="checkbox" id="doctor_${doctor.id}" name="associated_doctors[]" value="${doctor.id}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${isChecked ? 'checked' : ''}>
                    <label for="doctor_${doctor.id}" class="ml-2 text-sm font-medium text-gray-700">${htmlspecialchars(doctor.name)}</label>
                `;
                doctorsContainer.appendChild(checkboxDiv);
            });

        } catch (error) {
            console.error('Error al cargar los doctores o asociaciones:', error);
            doctorsContainer.innerHTML = '<p class="text-red-600">Error al cargar la lista de doctores. Por favor, intenta de nuevo.</p>';
        }

        toggleModal('manageServiceDoctorsModal', true);
    }

    // Slider Image
    function openWebSliderImageModal(imageId = null) {
        const modalTitle = document.getElementById('webSliderImageModalTitle');
        const form = document.getElementById('webSliderImageForm');
        const idInput = document.getElementById('webSliderImageId');
        const imageUrlInput = document.getElementById('webSliderImageUrl');
        const titleInput = document.getElementById('webSliderImageTitle');
        const orderInput = document.getElementById('webSliderImageOrder');

        form.reset();
        idInput.value = '';
        form.action = `${intranetBaseUrl}/configuracion-web/slider-images/create`;
        modalTitle.textContent = 'Nueva Imagen de Slider (Web)';

        if (imageId) {
            modalTitle.textContent = 'Editar Imagen de Slider (Web)';
            form.action = `${intranetBaseUrl}/configuracion-web/slider-images/update/${imageId}`;
            idInput.value = imageId;

            fetch(`${intranetBaseUrl}/api/web-config/slider-images/${imageId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(image => {
                    if (image) {
                        imageUrlInput.value = image.image_url;
                        titleInput.value = image.title;
                        orderInput.value = image.order_display;
                    } else {
                        alert('Imagen de slider no encontrada.');
                        toggleModal('webSliderImageModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching slider image details:', error);
                    alert('Error al cargar los detalles de la imagen de slider.');
                    toggleModal('webSliderImageModal', false);
                });
        }
        toggleModal('webSliderImageModal');
    }

    // Testimonial
    function openWebTestimonialModal(testimonialId = null) {
        const modalTitle = document.getElementById('webTestimonialModalTitle');
        const form = document.getElementById('webTestimonialForm');
        const idInput = document.getElementById('webTestimonialId');
        const quoteInput = document.getElementById('webTestimonialQuote');
        const authorInput = document.getElementById('webTestimonialAuthor');

        form.reset();
        idInput.value = '';
        form.action = `${intranetBaseUrl}/configuracion-web/testimonials/create`;
        modalTitle.textContent = 'Nuevo Testimonio (Web)';

        if (testimonialId) {
            modalTitle.textContent = 'Editar Testimonio (Web)';
            form.action = `${intranetBaseUrl}/configuracion-web/testimonials/update/${testimonialId}`;
            idInput.value = testimonialId;

            fetch(`${intranetBaseUrl}/api/web-config/testimonials/${testimonialId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(testimonial => {
                    if (testimonial) {
                        quoteInput.value = testimonial.quote;
                        authorInput.value = testimonial.author;
                    } else {
                        alert('Testimonio no encontrado.');
                        toggleModal('webTestimonialModal', false);
                    }
                })
                .catch(error => {
                    console.error('Error fetching testimonial details:', error);
                    alert('Error al cargar los detalles del testimonio.');
                    toggleModal('webTestimonialModal', false);
                });
        }
        toggleModal('webTestimonialModal');
    }


    // Helper function for PHP htmlspecialchars equivalent in JS
    function htmlspecialchars(str) {
        if (typeof str != "string") return str; // handle numbers, nulls etc.
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return str.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

</script>
<style>
    /* Estilos para los modales */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .contenido{
        max-height: 500px;
    overflow: scroll;
    max-width: 800px;

    }

    .modal-content {
        background-color: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        max-width: 90%;
        width: 600px; /* Ancho máximo para la mayoría de los modales */
        max-height: 90vh; /* Altura máxima para evitar desbordamientos */
        overflow-y: auto; /* Habilitar scroll si el contenido es demasiado largo */
        position: relative;
    }

    /* Ocultar el scroll del body cuando el modal está activo */
    body.overflow-hidden {
        overflow: hidden;
    }
</style>
