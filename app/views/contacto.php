<?php $pageTitle = 'Contacto - Centro Médico Integral El Ángel'; ?>
<section id="contact" class="py-12 bg-white text-gray-800">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-blue-900 mb-20 flex items-center justify-center gap-3">
            <!-- Icono Mail -->
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-10 w-10 text-blue-900">
                <rect width="20" height="16" x="2" y="4" rx="2"/>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
            Contáctanos
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Datos de Contacto -->
            <div class="bg-green-50 p-8 rounded-xl shadow-lg border border-blue-200">
                <h3 class="text-3xl font-semibold text-blue-900 mb-6">Nuestros Datos</h3>
                <div class="flex items-center mb-4 text-gray-900 text-lg">
                    <!-- Icono Phone -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone h-6 w-6 text-blue-900 mr-3">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2.01l-4.14-1.15a2 2 0 0 0-1.74.5l-3.37 3.37a2 2 0 0 1-2.47.07l-3.37-3.37a9.23 9.23 0 0 1-2.22-2.22l-3.37-3.37a2 2 0 0 0 .5-1.74L2.01 4.18A2 2 0 0 1 4 2h3a2 2 0 0 1 2 2 9.23 9.23 0 0 0 2.22 2.22l3.37 3.37a2 2 0 0 0-.07 2.47z"/>
                    </svg>
                    <span>Teléfono: <?php echo htmlspecialchars($phone); ?></span>
                </div>
                <div class="flex items-center mb-4 text-gray-900 text-lg">
                    <!-- Icono Mail -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-6 w-6 text-blue-600 mr-3">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                    <span>Email: <?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="flex items-start text-gray-900 text-lg">
                    <!-- Icono MapPin -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-6 w-6 text-blue-600 mr-3 mt-1">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span>Dirección: <?php echo htmlspecialchars($address); ?></span>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div class="bg-green-50 p-8 rounded-xl shadow-lg border border-blue-200">
                <h3 class="text-3xl font-semibold text-blue-900 mb-6">Envíanos un Mensaje</h3>
                <?php
                // Muestra el mensaje de la sesión si existe
                
                if (isset($_SESSION['form_message'])) {
                    echo '<p class="text-green-600 mb-4">' . htmlspecialchars($_SESSION['form_message']) . '</p>';
                    unset($_SESSION['form_message']); // Limpia el mensaje después de mostrarlo
                }
                ?>
                <form action="/elangel_medical_center/submit-contact" method="POST" class="space-y-4">
                    <div>
                        <label for="name" class="block text-gray-900 text-sm font-bold mb-2">Nombre:</label>
                        <input type="text" id="name" name="name" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="Tu Nombre" required />
                    </div>
                    <div>
                        <label for="email" class="block text-gray-900 text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" name="email" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="tu.email@ejemplo.com" required />
                    </div>
                    <div>
                        <label for="message" class="block text-gray-900 text-sm font-bold mb-2">Mensaje:</label>
                        <textarea id="message" name="message" rows="5" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    </div>
                    <button type="submit" class="degradado-horizontal hover:bg-blue-900 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-colors duration-300">
                        Enviar Mensaje
                    </button>
                    <p class="text-sm text-gray-500 mt-2">
                        <span class="font-bold">Nota:</span> Este formulario es de demostración. La funcionalidad de envío real requeriría configuración del servidor de correo.
                    </p>
                </form>
            </div>
        </div>

        <!-- Mapa de Google Maps -->
        <div class="mt-12">
            <h3 class="text-3xl font-semibold text-center text-blue-900 mb-6 flex items-center justify-center gap-2">
                <!-- Icono MapPin -->
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-8 w-8 text-blue-600">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Nuestra Ubicación
            </h3>
            <div class="rounded-xl overflow-hidden shadow-lg border border-blue-200">
                <iframe
                    src="<?php echo htmlspecialchars($googleMapsSrc); ?>"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Ubicación de El Ángel Centro Médico Integral"
                    class="rounded-xl"
                ></iframe>
            </div>
        </div>
    </div>
</section>