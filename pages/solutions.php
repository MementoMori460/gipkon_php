<?php
// pages/solutions.php
require_once __DIR__ . '/../functions.php';
render_header();
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-display font-bold mb-6">
                    Sektörel Çözümlerimiz
                </h1>
                <p class="text-xl text-primary-100">
                    <?php 
                    $activeSectors = array_filter($sectors, function($s) { return (!isset($s['isActive']) || $s['isActive'] !== false); });
                    echo count($activeSectors); 
                    ?> farklı sektörde uzmanlaşmış otomasyon çözümleri sunuyoruz
                </p>
            </div>
        </div>
    </section>

    <!-- Sectors Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($sectors as $sector): 
                    if (isset($sector['isActive']) && $sector['isActive'] === false) continue;
                ?>
                    <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all h-full flex flex-col">
                        <?php if (!empty($sector['image'])): ?>
                            <div class="relative h-48 overflow-hidden">
                                <span class="absolute top-4 right-4 z-10 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary-700 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                    İncele
                                </span>
                                <img 
                                    src="<?php echo $sector['image']; ?>" 
                                    alt="<?php echo $sector['title']; ?>" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                >
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-secondary-800 mb-2 group-hover:text-primary-700 transition-colors">
                                <?php echo $sector['title']; ?>
                            </h3>
                            <p class="text-secondary-600 text-sm mb-6 line-clamp-2 flex-grow">
                                <?php echo $sector['description']; ?>
                            </p>
                            <a href="/cozumler/<?php echo $sector['slug']; ?>" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 mt-auto">
                                Detaylı Bilgi <i data-lucide="arrow-right" class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                    Her Sektöre Özel Çözümler
                </h2>
                <p class="text-lg text-secondary-600 mb-8">
                    15+ yıllık deneyimimiz ve uzman kadromuz ile her sektörün özel
                    ihtiyaçlarına uygun otomasyon çözümleri geliştiriyoruz. Projeleriniz için
                    en uygun teknolojiyi seçiyor ve başarılı bir şekilde uyguluyoruz.
                </p>
            </div>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
