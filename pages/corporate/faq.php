<?php
// pages/corporate/faq.php
require_once __DIR__ . '/../../functions.php';
render_header();

$faqData = load_json('faq');
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-12 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900 via-primary-800 to-primary-900 opacity-90 z-0"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div class="inline-block p-3 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
                 <i data-lucide="help-circle" class="w-8 h-8 text-primary-200"></i>
            </div>
            <h1 class="text-3xl md:text-5xl font-display font-bold mb-6">
                Sıkça Sorulan Sorular
            </h1>
            <p class="text-lg md:text-xl text-primary-200 max-w-2xl mx-auto">
                Hizmetlerimiz ve süreçlerimiz hakkında merak ettiğiniz tüm soruların cevapları
            </p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="space-y-6">
                    <?php if (empty($faqData)): ?>
                        <p class="text-center text-gray-500">Henüz SSS eklenmemiş.</p>
                    <?php else: ?>
                        <?php foreach ($faqData as $item): ?>
                            <details class="group bg-white rounded-xl shadow-sm border border-gray-100 open:shadow-md transition-shadow">
                                <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-medium px-3 py-1 bg-primary-50 text-primary-700 rounded-full">
                                            <?php echo $item['category']; ?>
                                        </span>
                                        <h3 class="text-lg font-semibold text-secondary-800 group-hover:text-primary-700 transition-colors">
                                            <?php echo $item['question']; ?>
                                        </h3>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <i data-lucide="chevron-down" class="w-5 h-5 text-secondary-500 group-open:hidden transition-transform"></i>
                                        <i data-lucide="chevron-up" class="w-5 h-5 text-primary-600 hidden group-open:block transition-transform"></i>
                                    </div>
                                </summary>
                                <div class="px-6 pb-6 pt-0">
                                    <p class="text-secondary-600 leading-relaxed border-t border-gray-100 pt-4">
                                        <?php echo $item['answer']; ?>
                                    </p>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-display font-bold text-secondary-800 mb-8">
                Başka Sorunuz Var mı?
            </h2>
            <p class="text-lg text-secondary-600 mb-12 max-w-2xl mx-auto">
                Aradığınız cevabı bulamadınız mı? Bize doğrudan ulaşın, size yardımcı olmaktan memnuniyet duyarız.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-white p-8 rounded-xl shadow-sm text-center group hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-100 transition-colors">
                         <i data-lucide="phone" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Bizi Arayın</h3>
                    <p class="text-secondary-600 mb-4"><?php echo $settings['contact']['phone']; ?></p>
                    <a href="tel:<?php echo $settings['contact']['phone']; ?>" class="text-primary-600 font-medium hover:underline">
                        Hemen Ara
                    </a>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center group hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-100 transition-colors">
                         <i data-lucide="mail" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">E-posta Gönderin</h3>
                    <p class="text-secondary-600 mb-4"><?php echo $settings['contact']['email']; ?></p>
                    <a href="mailto:<?php echo $settings['contact']['email']; ?>" class="text-primary-600 font-medium hover:underline">
                        Mail Gönder
                    </a>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center group hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-100 transition-colors">
                         <i data-lucide="message-square" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">İletişim Formu</h3>
                    <p class="text-secondary-600 mb-4">Formu doldurun biz arayalım</p>
                    <a href="/iletisim" class="text-primary-600 font-medium hover:underline">
                        Forma Git
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
