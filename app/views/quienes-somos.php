<?php $pageTitle = 'Quienes Somos - Centro Médico Integral El Ángel'; ?>
<section id="about-us" class="py-12 bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-blue-900 mb-20 flex items-center justify-center gap-3">
            <!-- Icono Users -->
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-10 w-10 text-blue-900">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Quienes Somos
        </h2>
        <div class="bg-white p-8 rounded-xl shadow-lg mb-20 border border-blue-200">
            <h3 class="text-3xl font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <!-- Icono Lightbulb -->
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb h-7 w-7 text-blue-900">
                    <path d="M15 14c.2-.84.5-1.57.9-2.23A4.5 4.5 0 0 0 12 2C9.2 2 7 4.2 7 7c0 1.5.4 2.8.9 3.93c.4.76.7 1.6.9 2.23"></path>
                    <path d="M12 22v-2"></path>
                    <path d="M15 22h-6"></path>
                    <path d="M12 7h.01"></path>
                </svg>
                Nuestra Misión
            </h3>
            <p class="text-gray-900 text-lg text-justify indent-10 leading-relaxed mb-8">
                <?php echo htmlspecialchars($mission); ?>
            </p>

            <h3 class="text-3xl font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <!-- Icono Heart -->
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-7 w-7 text-red-900">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
                Nuestra Visión
            </h3>
            <p class="text-gray-900 text-lg text-justify indent-10 leading-relaxed">
                <?php echo htmlspecialchars($vision); ?>
            </p>
        </div>

        <!-- Galería de Imágenes -->
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-900 mb-8">Nuestras Instalaciones</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <img src="/elangel_medical_center/public/assets/img/galeria1.jpg" alt="Clínica Interior 1" class="w-full h-auto rounded-lg shadow-md object-cover" />
                <img src="/elangel_medical_center/public/assets/img/galeria2.jpg" alt="Clínica Interior 2" class="w-full h-auto rounded-lg shadow-md object-cover" />
                <img src="/elangel_medical_center/public/assets/img/galeria3.jpg" alt="Clínica Exterior" class="w-full h-auto rounded-lg shadow-md object-cover" />
                <img src="/elangel_medical_center/public/assets/img/galeria4.jpg" alt="Recepción" class="w-full h-auto rounded-lg shadow-md object-cover" />
                <img src="/elangel_medical_center/public/assets/img/galeria5.jpg"alt="Consultorio" class="w-full h-auto rounded-lg shadow-md object-cover" />
                <img src="/elangel_medical_center/public/assets/img/galeria6.jpg" alt="Laboratorio" class="w-full h-auto rounded-lg shadow-md object-cover" />
            </div>
        </div>
    </div>
</section>