<?php $pageTitle = 'FAQ - Centro Médico Integral El Ángel'; ?>
<section id="faq-section" class="py-12 bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-blue-900 mb-10 flex items-center justify-center gap-3">
            <!-- Icono MessageCircle -->
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-10 w-10 text-blue-900">
                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
            </svg>
            Preguntas Frecuentes (FAQ)
        </h2>
        <div class="max-w-3xl mx-auto space-y-4">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="bg-white rounded-xl shadow-md border border-blue-200 overflow-hidden">
                    <button id="faq-button-<?php echo $index; ?>"
                        class="w-full flex justify-between items-center p-5 text-left text-lg font-semibold text-blue-900 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                        onclick="toggleFAQ(<?php echo $index; ?>)">
                        <?php echo htmlspecialchars($faq['question']); ?>
                        <!-- Icono ChevronRight -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-6 w-6 transform transition-transform duration-300">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                    <div class="faq-answer" id="faq-<?php echo $index; ?>">
                        <div class="p-5 border-t border-blue-100 text-gray-900 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>