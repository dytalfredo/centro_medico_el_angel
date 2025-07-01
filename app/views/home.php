<?php $pageTitle = 'Inicio - Centro Médico Integral El Ángel'; ?>
<section id="home-main" class="py-4 bg-white text-gray-800">
 
    <div class="slider-container mb-12">
        <div class="slider-wrapper" id="sliderWrapper">
            <?php foreach ($sliderImages as $image): ?>
                <div class="slider-slide">
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" />
                    <div class="slider-overlay"><h3 class="text-white text-3xl font-bold"><?php echo htmlspecialchars($image['title']); ?></h3></div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="slider-button left" onclick="prevSlide()">
            <!-- Icono ChevronLeft -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left icon text-gray-800">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>
        <button class="slider-button right" onclick="nextSlide()">
            <!-- Icono ChevronRight -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right icon text-gray-800">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </button>
    </div>

    <div class="container mx-auto px-4">
        <!-- Llamada a la Acción -->
         <!-- Misión de la Empresa -->
        <div class="bg-green-50 p-8 rounded-xl shadow-lg mb-20 border border-blue-200">
            <h3 class="text-3xl font-semibold text-blue-900 mb-4 flex items-center justify-center gap-2">

                Nuestra Misión
            </h3>
            <p class="text-gray- 900 text-lg leading-relaxed text-center">
                <?php echo htmlspecialchars($mission); ?>
            </p>
        </div>

        <div class="text-center mb-20">
            <h2 class="text-4xl font-bold text-blue-900 mb-4">¿Necesitas Atención Médica?</h2>
            <p class="text-lg text-gray-600 mb-6">Estamos aquí para ayudarte a cuidar tu salud. Contáctanos hoy mismo.</p>
            <a href="/elangel_medical_center/contacto" class="degradado-horizontal  hover:scale-105 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300">
                Contáctanos Ahora
            </a>
        </div>

        

        <!-- Testimonios -->
        <div class="text-center">
            <h3 class="text-3xl font-bold text-blue-900 mb-8">Lo que dicen nuestros pacientes</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="bg-gradient-to-br from-green-100 to-green-50 p-6 rounded-xl shadow-md border border-blue-200 hover:shadow-xl transition-shadow duration-300">
                        <!-- Icono MessageCircle -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-10 w-10 text-blue-900 mx-auto mb-4">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                        </svg>
                        <p class="text-gray-900 italic mb-4">"<?php echo htmlspecialchars($testimonial['quote']); ?>"</p>
                        <p class="font-semibold text-blue-900">- <?php echo htmlspecialchars($testimonial['author']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>