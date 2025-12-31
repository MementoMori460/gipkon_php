<?php
require_once 'functions.php';

// Simulate a "Page" component content
render_header();
?>

<!-- Add Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<main class="min-h-screen">
    <!-- Hero Section -->
    <div id="hero-slider" class="relative h-[600px] overflow-hidden bg-black">
        <?php foreach ($hero as $index => $slide): ?>
            <div class="hero-slide absolute inset-0 transition-opacity duration-700 <?php echo $index === 0 ? 'opacity-100' : 'opacity-0'; ?>" data-index="<?php echo $index; ?>">
                <!-- Background Image -->
                <?php if (!empty($slide['image'])): ?>
                <img 
                    src="<?php echo $slide['image']; ?>" 
                    alt="<?php echo $slide['title']; ?>" 
                    class="absolute inset-0 w-full h-full object-cover"
                >
                <?php endif; ?>

                <!-- Overlay -->
                <div class="absolute inset-0" style="background-color: var(--hero-overlay); opacity: var(--hero-overlay-opacity);"></div>

                <!-- Content -->
                <div class="relative h-full container mx-auto px-4 flex items-center">
                    <div class="max-w-2xl text-white">
                        <?php if (!empty($slide['subtitle'])): ?>
                            <p class="text-primary-400 font-semibold mb-2 animate-fade-in">
                                <?php echo $slide['subtitle']; ?>
                            </p>
                        <?php endif; ?>
                        
                        <h1 class="text-5xl md:text-6xl font-display font-bold mb-4 animate-slide-up" style="color: var(--hero-text)">
                            <?php echo $slide['title']; ?>
                        </h1>
                        
                        <?php if (!empty($slide['description'])): ?>
                            <p class="text-xl mb-8 animate-slide-up" style="color: var(--hero-text); opacity: 0.9;">
                                <?php echo $slide['description']; ?>
                            </p>
                        <?php endif; ?>

                        <div class="flex flex-wrap gap-4 animate-fade-in">
                            <?php if (!empty($slide['cta'])): ?>
                                <a href="<?php echo $slide['cta']['href']; ?>" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary-600 text-white hover:bg-primary-700 h-11 px-8">
                                    <?php echo $slide['cta']['text']; ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['secondaryCta'])): ?>
                                <a href="<?php echo $slide['secondaryCta']['href']; ?>" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-white/10 backdrop-blur-sm border border-white text-white hover:bg-white hover:text-primary-700 h-11 px-8">
                                    <?php echo $slide['secondaryCta']['text']; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- App Icons (Replaced with Lucide via JS) -->
        <button id="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 flex items-center justify-center transition-colors z-20">
            <i data-lucide="chevron-left" class="text-white"></i>
        </button>
        <button id="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 flex items-center justify-center transition-colors z-20">
             <i data-lucide="chevron-right" class="text-white"></i>
        </button>

        <!-- Dots -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-20">
            <?php foreach ($hero as $index => $slide): ?>
                <button class="slider-dot w-3 h-3 rounded-full transition-all <?php echo $index === 0 ? 'bg-primary-500 w-8' : 'bg-white/50 hover:bg-white/75'; ?>" onclick="goToSlide(<?php echo $index; ?>)"></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Stats Section -->
    <section class="py-16 bg-primary-700 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 text-primary-300"></i>
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-primary-200">Mutlu Müşteri</div>
                </div>
                <div class="text-center">
                    <i data-lucide="award" class="w-12 h-12 mx-auto mb-4 text-primary-300"></i>
                    <div class="text-4xl font-bold mb-2">15+</div>
                    <div class="text-primary-200">Yıllık Deneyim</div>
                </div>
                <div class="text-center">
                    <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-4 text-primary-300"></i>
                    <div class="text-4xl font-bold mb-2">1000+</div>
                    <div class="text-primary-200">Tamamlanan Proje</div>
                </div>
                <div class="text-center">
                    <i data-lucide="zap" class="w-12 h-12 mx-auto mb-4 text-primary-300"></i>
                    <div class="text-4xl font-bold mb-2">7/24</div>
                    <div class="text-primary-200">Teknik Destek</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-6">
                    Teknolojiye ve Geleceğe Yön Veren Firma
                </h2>
                <p class="text-lg text-secondary-600 mb-8">
                    Firmamız kurulduğu yıldan itibaren sürekli kendini geliştiren, yenilikleri takip eden
                    ve bu doğrultuda hizmet veren otomasyon çözüm firmasıdır. Müşteri memnuniyetini ilke
                    edinerek yolumuza devam etmekteyiz.
                </p>
                <a href="/kurumsal/hakkimizda" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors bg-primary-600 text-white hover:bg-primary-700 h-11 px-8">
                    Hakkımızda <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-4">
                    Sektörel Çözümlerimiz
                </h2>
                <p class="text-lg text-secondary-600 max-w-2xl mx-auto">
                    <?php echo count(array_filter($sectors, function($s){ return (!isset($s['isActive']) || $s['isActive']); })); ?> farklı sektörde uzmanlaşmış otomasyon çözümleri sunuyoruz
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($sectors as $sector): 
                    if (isset($sector['isActive']) && $sector['isActive'] === false) continue;
                ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                         <?php if (!empty($sector['image'])): ?>
                            <div class="relative h-48 overflow-hidden">
                                <img src="<?php echo $sector['image']; ?>" alt="<?php echo $sector['title']; ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-secondary-800 mb-2"><?php echo $sector['title']; ?></h3>
                            <p class="text-secondary-600 text-sm mb-4 line-clamp-3"><?php echo $sector['description']; ?></p>
                            <a href="/cozumler/<?php echo $sector['slug']; ?>" class="text-primary-600 font-medium hover:text-primary-700 flex items-center gap-1 text-sm">
                                İncele <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
             <div class="text-center mt-12">
                <a href="/cozumler" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-8">
                     Tüm Çözümler <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-4">
                    Hizmetlerimiz
                </h2>
                <p class="text-lg text-secondary-600 max-w-2xl mx-auto">
                    Proje danışmanlığından devreye almaya kadar tam kapsamlı hizmet
                </p>
            </div>
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach (array_slice($services, 0, 6) as $service): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
                        <h3 class="text-xl font-bold text-secondary-800 mb-2"><?php echo $service['title']; ?></h3>
                        <p class="text-secondary-600 text-sm mb-4 line-clamp-3">
                            <?php echo $service['description']; ?>
                        </p>
                        <a href="/hizmetler/<?php echo $service['slug']; ?>" class="mt-auto text-primary-600 font-medium hover:text-primary-700 flex items-center gap-1 text-sm">
                             Detayları İncele <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-12">
                <a href="/hizmetler" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-8">
                        Tüm Hizmetler <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary-700 to-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-display font-bold mb-6">
                Projeniz İçin Hemen İletişime Geçin
            </h2>
            <p class="text-xl mb-8 text-primary-100 max-w-2xl mx-auto">
                Uzman ekibimiz size en uygun otomasyon çözümünü sunmak için hazır
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/iletisim" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors bg-white text-primary-700 hover:bg-gray-100 h-11 px-8">
                    İletişime Geç
                </a>
                <a href="/hizmet-talebi" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors border border-white text-white hover:bg-white/10 h-11 px-8">
                    Teklif Al
                </a>
            </div>
        </div>
    </section>

</main>

<script>
    // Lucide Icons
    lucide.createIcons();

    // Hero Slider Logic
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    let currentSlide = 0;
    const intervalTime = 5000;
    let slideInterval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/50', 'hover:bg-white/75');
                dot.classList.add('bg-primary-500', 'w-8');
            } else {
                dot.classList.add('bg-white/50', 'hover:bg-white/75');
                dot.classList.remove('bg-primary-500', 'w-8');
            }
        });
        currentSlide = index;
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    document.getElementById('nextSlide').addEventListener('click', () => {
        nextSlide();
        resetInterval();
    });

    document.getElementById('prevSlide').addEventListener('click', () => {
        prevSlide();
        resetInterval();
    });

    // Make dot clicks work
    window.goToSlide = function(index) {
        showSlide(index);
        resetInterval();
    }

    function startSlider() {
        slideInterval = setInterval(nextSlide, intervalTime);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startSlider();
    }

    startSlider();
</script>

<?php
render_footer();
?>
