<?php $pageTitle = 'Nuestros Servicios y Especialistas - Centro Médico Integral El Ángel'; ?>
<section id="services-section" class="py-12 bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 ">
        <h2 class="text-4xl font-bold text-center text-blue-900 mb-20 flex items-center justify-center gap-3">
            <!-- Icono Hospital -->
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hospital h-10 w-10 text-blue-900">
                <path d="M12 6v4a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2v4"></path>
                <path d="M11 19H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-5.5"></path>
                <path d="M6 14h0"></path>
                <path d="M14 14h0"></path>
            </svg>
            Nuestros Servicios y Especialistas
        </h2>

        <div class="overflow-x-auto rounded-lg shadow-xl border border-blue-200">
            <table class="min-w-full divide-y divide-green-200 bg-white">
                <thead class="bg-green-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider rounded-tl-lg">
                            Servicio/Especialidad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider rounded-tr-lg">
                            Doctores
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    <?php
                    // Icon mapping for services (can be moved to a helper or passed from controller)
                    $serviceIcons = [
                        'Oncólogo' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope w-5 h-5 text-blue-500"><path d="M11.3 18.7a2.5 2.5 0 0 1-3.1-3.1L12 12l3.1 3.1c-.7.7-1.5 1.1-2.2 1.6-1.3.9-2.5 1.5-3.3 2Z"/><path d="M18 12c-1.3-1.3-2.7-2-4-2h-4v-.3A2.7 2.7 0 0 1 9 6.7c0-.9.7-1.7 1.7-1.7h.6A2.7 2.7 0 0 1 14 6.7v.3h-4"/><path d="M22 8v2a10 10 0 0 1-10 10H8"/><path d="M2 12V8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Z"/></svg>',
                        'Hematólogo' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-syringe w-5 h-5 text-red-500"><path d="m14 11 2 2 4-4"/><path d="M17 14c-1.5 1.5-3 2-3 2l-7 7H3v-4l7-7s.5-1.5 2-3C15.5 5.5 17 4 17 4c1.5-1.5 3-1.5 3-1.5v-.5a2 2 0 0 0-2-2h-3.5c-.5 0-1 .5-1.5 1s-1.5 1.5-3 3c-1.5 1.5-2 3-2 3L7.5 11.5"/></svg>',
                        'Endocrino' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope w-5 h-5 text-green-500"><path d="M11.3 18.7a2.5 2.5 0 0 1-3.1-3.1L12 12l3.1 3.1c-.7.7-1.5 1.1-2.2 1.6-1.3.9-2.5 1.5-3.3 2Z"/><path d="M18 12c-1.3-1.3-2.7-2-4-2h-4v-.3A2.7 2.7 0 0 1 9 6.7c0-.9.7-1.7 1.7-1.7h.6A2.7 2.7 0 0 1 14 6.7v.3h-4"/><path d="M22 8v2a10 10 0 0 1-10 10H8"/><path d="M2 12V8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Z"/></svg>',
                        'Pediatra' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-5 h-5 text-yellow-500"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                        'Psiquiatra' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-brain w-5 h-5 text-purple-500"><path d="M9 18a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h4l2 2v4a2 2 0 0 1-2 2h-4z"/><path d="M2 10a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4zM22 10a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4zM16 18a2 2 0 0 1 2-2v-4a2 2 0 0 1-2-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4z"/></svg>',
                        'Diag.por Imag.' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-microscope w-5 h-5 text-indigo-500"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 10h1.5a.5.5 0 0 0 0-1H14V6h-.5a.5.5 0 0 0-.5.5v.5h-.5a.5.5 0 0 0-.5.5v.5h-.5a.5.5 0 0 0-.5.5V10h-.5a.5.5 0 0 0-.5-.5v-.5h-.5a.5.5 0 0 0-.5-.5V8.5h-.5a.5.5 0 0 0-.5-.5v-.5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2H9"/></svg>',
                        'Nutrición' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 text-pink-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
                        'Radioterapeuta' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-radiation w-5 h-5 text-orange-500"><path d="M12 2v2"/><path d="M10 22h4"/><path d="M17.43 3.65 16.09 5"/><path d="M8.57 20.35 7.23 19"/><path d="M22 12h-2"/><path d="M4 12H2"/><path d="m20.35 7.23-1.34-1.34"/><path d="m5 16.09-1.34 1.34"/><path d="m19 19-1.41-1.41"/><path d="m7.87 5.71-1.41 1.41"/><path d="m15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>',
                        'Gineco-obstetra' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope w-5 h-5 text-teal-500"><path d="M11.3 18.7a2.5 2.5 0 0 1-3.1-3.1L12 12l3.1 3.1c-.7.7-1.5 1.1-2.2 1.6-1.3.9-2.5 1.5-3.3 2Z"/><path d="M18 12c-1.3-1.3-2.7-2-4-2h-4v-.3A2.7 2.7 0 0 1 9 6.7c0-.9.7-1.7 1.7-1.7h.6A2.7 2.7 0 0 1 14 6.7v.3h-4"/><path d="M22 8v2a10 10 0 0 1-10 10H8"/><path d="M2 12V8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Z"/></svg>',
                        'Fonoaudiología' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ear w-5 h-5 text-cyan-500"><path d="M6 18c-3.3-1.5-4-6.4-1.4-9.3 2.5-2.7 6.4-2.2 8.2-1C13.9 8 13.9 14 10 17z"/><path d="M13.5 15.5c-.7.7-1.6 1.4-2 2"/><path d="M2 8a2 2 0 0 1 2-2h3"/><path d="M7 2h3a2 2 0 0 1 2 2v3"/><path d="M18 10a2 2 0 0 0-2 2v3"/><path d="M15 18a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-3"/></svg>',
                        'Otorrinolaringólogo' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ear w-5 h-5 text-lime-500"><path d="M6 18c-3.3-1.5-4-6.4-1.4-9.3 2.5-2.7 6.4-2.2 8.2-1C13.9 8 13.9 14 10 17z"/><path d="M13.5 15.5c-.7.7-1.6 1.4-2 2"/><path d="M2 8a2 2 0 0 1 2-2h3"/><path d="M7 2h3a2 2 0 0 1 2 2v3"/><path d="M18 10a2 2 0 0 0-2 2v3"/><path d="M15 18a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-3"/></svg>',
                        'Traumatología' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bone w-5 h-5 text-amber-500"><path d="M17 10c.5-1 2-2 3-2a2 2 0 0 1 0 4c-1 0-2-1-3-2L9 2l-2 2 4 4-2 2 1-1 4 4-1 1 2 2 2-2 4 4 1-1 2 2c1 0 2-1 3-2a2 2 0 0 1 0 4c-1 0-2-1-3-2"/></svg>',
                        'Servicio de RX' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-microscope w-5 h-5 text-gray-500"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 10h1.5a.5.5 0 0 0 0-1H14V6h-.5a.5.5 0 0 0-.5.5v.5h-.5a.5.5 0 0 0-.5.5v.5h-.5a.5.5 0 0 0-.5.5V10h-.5a.5.5 0 0 0-.5-.5v-.5h-.5a.5.5 0 0 0-.5-.5V8.5h-.5a.5.5 0 0 0-.5-.5v-.5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2H9"/></svg>',
                        'Serv. De Laboratorio' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-microscope w-5 h-5 text-blue-gray-500"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 10h1.5a.5.5 0 0 0 0-1H14V6h-.5a.5.5 0 0 0-.5.5v.5h-.5a.5.5 0 0 0-.5.5v.5h-.5a.5.5 0 0 0-.5.5V10h-.5a.5.5 0 0 0-.5-.5v-.5h-.5a.5.5 0 0 0-.5-.5V8.5h-.5a.5.5 0 0 0-.5-.5v-.5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2H9"/></svg>',
                        'Angiólogo' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 text-red-900"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
                    ];

                    foreach ($servicesAndDoctors as $index => $item):
                    ?>
                        <tr class="<?php echo $index % 2 === 0 ? 'bg-white' : 'bg-green-50'; ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <?php echo $serviceIcons[$item['service']] ?? '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-circle w-5 h-5 text-gray-500"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>'; ?>
                                    <span class="ml-3 text-lg"><?php echo htmlspecialchars($item['service']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                <ul class="list-disc list-inside space-y-1">
                                    <?php foreach ($item['doctors'] as $doctor): ?>
                                        <li><?php echo htmlspecialchars($doctor); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-center text-gray-600">
            <p class="text-sm">
                Para más información o para agendar una cita con nuestros especialistas, por favor, póngase en contacto con nosotros.
            </p>
        </div>
    </div>
</section>