</main>

<footer class="degradado-horizontal text-white py-6 md:py-8 mt-8 md:mt-12">
    <div class="container mx-auto px-4 text-center">
        <p class="text-base md:text-lg mb-4">Centro Médico Integral El Ángel &copy; <?php echo date('Y'); ?>. Todos los derechos reservados.</p>
        <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-y-0 md:space-x-6 text-sm">
            <a href="<?php echo BASE_URL; ?>/aviso-legal" class="hover:text-indigo-200 transition-colors duration-200">Aviso Legal</a>
            <a href="<?php echo BASE_URL; ?>/politica-privacidad" class="hover:text-indigo-200 transition-colors duration-200">Términos de Servicio</a>
        </div>
    </div>
</footer>

<?php if (isset($currentPage)): ?>
    <script>
        console.log('Valor de $currentPage: "<?php echo $currentPage; ?>"');
    </script>
<?php endif; ?>

<!-- Script para el slider en la página de inicio (solo si es home) -->
<?php if (isset($currentPage) && $currentPage == 'home'): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Iniciando script del slider.');

        let currentSlide = 0;
        const slides = document.querySelectorAll('.slider-slide');
        const sliderWrapper = document.getElementById('sliderWrapper');

        console.log('Slides encontrados:', slides.length);
        console.log('Slider Wrapper encontrado:', sliderWrapper);

        // Importante: Verifica si los elementos existen antes de intentar manipularlos
        if (!sliderWrapper || slides.length === 0) {
            console.warn('Elementos del slider no encontrados. El slider podría no funcionar correctamente.');
            return;
        }

        function showSlide(index) {
            currentSlide = (index + slides.length) % slides.length;
            const offset = -currentSlide * 100;
            sliderWrapper.style.transform = `translateX(${offset}vw)`; // Usar vw para el ancho completo del viewport
            console.log(`Cambiando a slide ${currentSlide}. Transform: translateX(${offset}vw)`);
        }

        window.nextSlide = function() {
            console.log('Función nextSlide llamada.');
            showSlide(currentSlide + 1);
        };

        window.prevSlide = function() {
            console.log('Función prevSlide llamada.');
            showSlide(currentSlide - 1);
        };

        // Inicializa el slider en la primera diapositiva al cargar la página
        showSlide(currentSlide);
        console.log('Slider inicializado.');

        let slideInterval = setInterval(window.nextSlide, 5000); // Avance automático cada 5 segundos
        console.log('Intervalo de slider automático iniciado.');

        const sliderContainer = document.querySelector('.slider-container');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
                console.log('Slider pausado (mouseEnter).');
            });
            sliderContainer.addEventListener('mouseleave', () => {
                slideInterval = setInterval(window.nextSlide, 5000);
                console.log('Slider reanudado (mouseLeave).');
            });
        }

        // Ajustar la posición del slider al cambiar el tamaño de la ventana
        window.addEventListener('resize', () => {
            console.log('Evento resize detectado.');
            showSlide(currentSlide); // Recalcula y aplica la posición basándose en el nuevo ancho del viewport
        });
    });
</script>
<?php endif; ?>

<!-- Script para el acordeón de FAQ (solo si es faq) -->
<?php if (isset($currentPage) && $currentPage == 'faq'): ?>
<script>
    function toggleFAQ(index) {
        const answer = document.getElementById(`faq-${index}`);
        const buttonIcon = document.querySelector(`#faq-button-${index} svg`); // Selecciona el SVG dentro del botón
        if (answer.classList.contains('expanded')) {
            answer.classList.remove('expanded');
            answer.style.maxHeight = null; // Reinicia max-height para la transición de cierre
            buttonIcon.classList.remove('rotate-90');
        } else {
            // Close all other expanded FAQs
            document.querySelectorAll('.faq-answer.expanded').forEach(item => {
                item.classList.remove('expanded');
                item.style.maxHeight = null;
                item.previousElementSibling.querySelector('svg').classList.remove('rotate-90');
            });
            answer.classList.add('expanded');
            // Ajusta max-height dinámicamente para la transición de apertura
            // + 20px extra para un pequeño margen si el contenido es justo
            answer.style.maxHeight = (answer.scrollHeight + 20) + "px";
            buttonIcon.classList.add('rotate-90');
        }
    }
    // Ajustar max-height en resize para el acordeón FAQ
    window.addEventListener('resize', () => {
        document.querySelectorAll('.faq-answer.expanded').forEach(item => {
            item.style.maxHeight = item.scrollHeight + "px";
        });
    });
</script>
<?php endif; ?>

</body>
</html>