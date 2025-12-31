<?php
// pages/solution-detail.php
require_once __DIR__ . '/../functions.php';

// Get slug from query param (set in router)
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Find sector
$currentSector = null;
foreach ($sectors as $s) {
    if ($s['slug'] === $slug) {
        $currentSector = $s;
        break;
    }
}

if (!$currentSector) {
    http_response_code(404);
    // Simple 404 Page
    render_header(['title' => 'Sektör Bulunamadı']);
    echo '<div class="min-h-screen flex items-center justify-center bg-gray-50 flex-col">';
    echo '<h1 class="text-4xl font-bold text-gray-800 mb-4">404</h1>';
    echo '<p class="text-gray-600 mb-8">Aradığınız sektör sayfası bulunamadı.</p>';
    echo '<a href="/cozumler" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Çözümler\'e Dön</a>';
    echo '</div>';
    render_footer();
    exit;
}

render_header(['title' => $currentSector['title'] . ' - Gipkon Çözümler']);
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[400px]">
        <?php if (!empty($currentSector['image'])): ?>
            <div class="absolute inset-0 z-0">
                 <div class="absolute inset-0 bg-gradient-to-r from-primary-900/90 via-primary-800/80 to-transparent z-10"></div>
                <img
                    src="<?php echo $currentSector['image']; ?>"
                    alt="<?php echo $currentSector['title']; ?>"
                    class="w-full h-full object-cover object-center"
                />
            </div>
        <?php else: ?>
             <div class="absolute inset-0 bg-primary-800 z-0"></div>
        <?php endif; ?>
        
        <div class="absolute inset-0 z-20 container mx-auto px-4 h-full flex flex-col justify-end pb-12">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-2 animate-slide-up">
                <?php echo $currentSector['title']; ?>
            </h1>
        </div>
    </div>

    <!-- Content Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8 order-2 lg:order-1">
                    <!-- Navigation -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gray-50 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-secondary-800">
                                Diğer Çözümlerimiz
                            </h3>
                        </div>
                        <nav class="p-2">
                            <?php foreach ($sectors as $s): 
                                if (isset($s['isActive']) && $s['isActive'] === false) continue;
                            ?>
                                <a 
                                    href="/cozumler/<?php echo $s['slug']; ?>" 
                                    class="flex items-center justify-between p-3 rounded-lg transition-all mb-1 last:mb-0 <?php echo $s['slug'] === $slug ? 'bg-primary-50 text-primary-700 font-medium' : 'text-secondary-600 hover:bg-gray-50 hover:text-primary-600'; ?>"
                                >
                                    <span><?php echo $s['title']; ?></span>
                                    <?php if ($s['slug'] === $slug): ?>
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </div>

                    <!-- Contact Card -->
                    <div class="bg-primary-700 rounded-xl p-8 text-white relative overflow-hidden">
                        <!-- Decorative Circles -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full translate-x-1/3 -translate-y-1/3"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -translate-x-1/3 translate-y-1/3"></div>
                        
                        <h3 class="text-xl font-bold mb-4 relative z-10">Yardıma mı ihtiyacınız var?</h3>
                        <p class="text-primary-100 mb-6 text-sm relative z-10">
                            Projeniz için uzman ekibimizle iletişime geçin.
                        </p>
                        
                        <div class="space-y-4 relative z-10">
                            <a href="tel:+90 312 939 86 33" class="flex items-center gap-3 text-white hover:text-primary-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="phone" class="lucide lucide-phone w-5 h-5"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
                                <span class="font-medium">+90 312 939 86 33</span>
                            </a>
                            <a href="mailto:gipkon@gipkon.com.tr" class="flex items-center gap-3 text-white hover:text-primary-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="mail" class="lucide lucide-mail w-5 h-5"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
                                <span class="font-medium">gipkon@gipkon.com.tr</span>
                            </a>
                        </div>

                        <a href="/iletisim" class="mt-8 w-full block text-center bg-white text-primary-700 py-3 rounded-lg font-bold hover:bg-primary-50 transition-colors relative z-10">
                            İletişime Geçin
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 order-1 lg:order-2">
                    <div class="prose prose-lg max-w-none text-secondary-600">
                         <!-- Description -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
                            <h2 class="text-2xl font-bold text-secondary-800 mb-6 flex items-center gap-3">
                                <i data-lucide="info" class="w-6 h-6 text-primary-600"></i>
                                Sektör Hakkında
                            </h2>
                            <p class="leading-relaxed">
                                <?php echo $currentSector['description']; ?>
                            </p>
                        </div>

                        <!-- Features / Benefits (Mock content if not present in JSON) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                            <div class="bg-primary-50/50 p-6 rounded-xl border border-primary-100">
                                <i data-lucide="zap" class="w-8 h-8 text-primary-600 mb-4"></i>
                                <h3 class="font-bold text-gray-900 mb-2">Verimlilik Artışı</h3>
                                <p class="text-sm">Otomasyon çözümlerimizle üretim süreçlerinizde maksimum verimlilik sağlayın.</p>
                            </div>
                            <div class="bg-primary-50/50 p-6 rounded-xl border border-primary-100">
                                <i data-lucide="shield" class="w-8 h-8 text-primary-600 mb-4"></i>
                                <h3 class="font-bold text-gray-900 mb-2">Güvenilir Sistemler</h3>
                                <p class="text-sm">7/24 kesintisiz çalışan, dayanıklı ve güvenilir endüstriyel sistemler.</p>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="bg-gray-900 text-white rounded-2xl p-8 md:p-12 text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-[url('/assets/img/pattern.svg')] opacity-10"></div>
                            <div class="relative z-10">
                                <h3 class="text-2xl md:text-3xl font-bold mb-4">Bu sektörde çözüm ortağınız olalım</h3>
                                <p class="text-gray-400 mb-8 max-w-2xl mx-auto">Uzman ekibimizle projenizi hayata geçirmek için hazırız.</p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="/iletisim" class="px-8 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-500 transition-colors font-semibold">
                                        İletişime Geçin
                                    </a>
                                    <a href="/hizmetler" class="px-8 py-3 bg-white/10 text-white border border-white/20 rounded-lg hover:bg-white/20 transition-colors">
                                        Hizmetlerimiz
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    lucide.createIcons();
</script>

<?php render_footer(); ?>
