<?php
// pages/service-detail.php
require_once __DIR__ . '/../functions.php';

// Get slug from query param (set in router)
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Find service
$currentService = null;
foreach ($services as $s) {
    if ($s['slug'] === $slug) {
        $currentService = $s;
        break;
    }
}

if (!$currentService || (isset($currentService['isActive']) && $currentService['isActive'] === false)) {
    http_response_code(404);
    echo "Hizmet bulunamadı.";
    exit;
}

render_header();
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[50vh] min-h-[400px]">
        <?php if (!empty($currentService['image'])): ?>
            <div class="absolute inset-0 z-0">
                 <div class="absolute inset-0 bg-gradient-to-r from-secondary-900/90 to-secondary-800/80 z-10"></div>
                <img
                    src="<?php echo $currentService['image']; ?>"
                    alt="<?php echo $currentService['title']; ?>"
                    class="w-full h-full object-cover object-center"
                />
            </div>
        <?php else: ?>
             <div class="absolute inset-0 bg-secondary-900 z-0"></div>
        <?php endif; ?>
        

    </div>

    <!-- Content Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8 order-2 lg:order-1">
                    <!-- Navigation -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-secondary-800 mb-4 px-2 border-l-4 border-primary-500">
                            Diğer Hizmetlerimiz
                        </h3>
                        <nav class="space-y-2">
                            <?php foreach ($services as $s): 
                                if (isset($s['isActive']) && $s['isActive'] === false) continue;
                            ?>
                                <a  
                                    href="/hizmetler/<?php echo $s['slug']; ?>" 
                                    class="flex items-center justify-between p-3 rounded-lg transition-all <?php echo $s['slug'] === $slug ? 'bg-primary-600 text-white shadow-md' : 'text-secondary-600 hover:bg-white hover:shadow-sm hover:text-primary-600'; ?>"
                                >
                                    <span class="font-medium"><?php echo $s['title']; ?></span>
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
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
                            <a href="tel:<?php echo $settings['contact']['phone']; ?>" class="flex items-center gap-3 text-white hover:text-primary-200 transition-colors">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                                <span class="font-medium"><?php echo $settings['contact']['phone']; ?></span>
                            </a>
                            <a href="mailto:<?php echo $settings['contact']['email']; ?>" class="flex items-center gap-3 text-white hover:text-primary-200 transition-colors">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                                <span class="font-medium"><?php echo $settings['contact']['email']; ?></span>
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
                        <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                            <?php echo $currentService['title']; ?>
                        </h2>
                        
                        <p class="text-lg leading-relaxed mb-8">
                            <?php echo $currentService['description']; ?>
                        </p>

                        <div class="bg-primary-50 rounded-xl p-8 border border-primary-100 mb-12">
                            <h3 class="text-xl font-bold text-secondary-800 mb-6 flex items-center gap-3">
                                <i data-lucide="check-circle" class="w-6 h-6 text-primary-600"></i>
                                Hizmet Kapsamı
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($currentService['details'] as $detail): ?>
                                    <div class="flex items-start gap-3 bg-white p-4 rounded-lg shadow-sm border border-primary-100/50">
                                        <i data-lucide="check" class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"></i>
                                        <span class="text-secondary-700 font-medium"><?php echo $detail; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-secondary-800 mb-4">Çalışma Sürecimiz</h3>
                        <div class="space-y-6 mb-12">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border-4 border-white shadow-sm">1</div>
                                <div>
                                    <h4 class="font-bold text-secondary-800 mb-2">Analiz ve Planlama</h4>
                                    <p class="text-sm">İhtiyaçlarınızı detaylı analiz ederek en uygun çözümü planlıyoruz.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border-4 border-white shadow-sm">2</div>
                                <div>
                                    <h4 class="font-bold text-secondary-800 mb-2">Tasarım ve Geliştirme</h4>
                                    <p class="text-sm">Modern teknolojileri kullanarak sistem tasarımını gerçekleştiriyoruz.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border-4 border-white shadow-sm">3</div>
                                <div>
                                    <h4 class="font-bold text-secondary-800 mb-2">Kurulum ve Test</h4>
                                    <p class="text-sm">Sistemi kuruyor ve tüm fonksiyon testlerini tamamlıyoruz.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border-4 border-white shadow-sm">4</div>
                                <div>
                                    <h4 class="font-bold text-secondary-800 mb-2">Teslim ve Destek</h4>
                                    <p class="text-sm">Projeyi teslim ediyor ve 7/24 teknik destek sağlıyoruz.</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-secondary-800 mb-4">Neden Bizi Seçmelisiniz?</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <i data-lucide="award" class="w-8 h-8 text-primary-600 mb-4"></i>
                                <h4 class="font-bold text-secondary-800 mb-2">Uzman Kadro</h4>
                                <p class="text-sm text-secondary-600">Alanında deneyimli mühendis ve teknisyenler.</p>
                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <i data-lucide="shield-check" class="w-8 h-8 text-primary-600 mb-4"></i>
                                <h4 class="font-bold text-secondary-800 mb-2">Garantili Hizmet</h4>
                                <p class="text-sm text-secondary-600">Tüm projelerimizde garanti ve servis desteği.</p>
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
