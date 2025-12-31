<?php
require_once __DIR__ . '/../functions.php';

$heroSlides = load_json('hero');
render_header([
    'title' => 'Gipkon Otomasyon - Endüstriyel Çözümler',
    'description' => 'Gipkon Otomasyon, endüstriyel otomasyon çözümleri, yazılım geliştirme ve mühendislik hizmetleri sunmaktadır.'
]);
?>

<div class="relative bg-white overflow-hidden">
    <!-- Hero Slider -->
    <div class="relative h-[500px] md:h-[700px] w-full" id="hero-slider">
        <?php foreach ($heroSlides as $index => $slide): ?>
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out <?php echo $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?>" data-slide-index="<?php echo $index; ?>">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img src="<?php echo url($slide['image']); ?>" alt="<?php echo $slide['title']; ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-black/40 mix-blend-multiply"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-3xl">
                        <h2 class="text-secondary-400 font-semibold tracking-wide uppercase text-xs md:text-sm mb-2 md:mb-4 animate-fade-in-up">
                            <?php echo $slide['subtitle']; ?>
                        </h2>
                        <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 md:mb-6 leading-tight animate-fade-in-up" style="animation-delay: 0.2s">
                            <?php echo $slide['title']; ?>
                        </h1>
                        <p class="text-base md:text-xl text-gray-200 mb-6 md:mb-8 animate-fade-in-up" style="animation-delay: 0.4s">
                            <?php echo $slide['description']; ?>
                        </p>
                        <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.6s">
                            <?php if (isset($slide['cta'])): ?>
                                <a href="<?php echo url($slide['cta']['href']); ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center gap-2">
                                    <?php echo $slide['cta']['text']; ?>
                                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (isset($slide['secondaryCta'])): ?>
                                <a href="<?php echo url($slide['secondaryCta']['href']); ?>" class="bg-white/10 hover:bg-white/20 text-white border border-white/30 backdrop-blur-sm px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                                    <?php echo $slide['secondaryCta']['text']; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Slider Controls -->
        <div class="absolute bottom-10 left-0 right-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <!-- Indicators -->
                <div class="flex gap-2">
                    <?php foreach ($heroSlides as $index => $slide): ?>
                        <button onclick="goToSlide(<?php echo $index; ?>)" class="w-3 h-3 rounded-full transition-all duration-300 <?php echo $index === 0 ? 'bg-primary-500 w-8' : 'bg-white/50 hover:bg-white'; ?>" data-indicator-index="<?php echo $index; ?>"></button>
                    <?php endforeach; ?>
                </div>
                
                <!-- Arrows -->
                <div class="flex gap-2 hidden md:flex">
                    <button onclick="prevSlide()" class="p-2 rounded-full border border-white/30 text-white hover:bg-white/10 transition-colors backdrop-blur-sm">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>
                    <button onclick="nextSlide()" class="p-2 rounded-full border border-white/30 text-white hover:bg-white/10 transition-colors backdrop-blur-sm">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    


    <!-- References Section -->
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-12">Referanslarımız</h2>
            
            <?php $references = load_json('references'); ?>
            
            <div class="relative overflow-hidden group">
                <!-- Carousel Container -->
                <div class="flex space-x-12 animate-scroll w-max">
                    <?php 
                    // Duplicate items for scrolling
                    $displayRefs = array_merge($references, $references); 
                    foreach ($displayRefs as $ref): 
                        if (empty($ref['image'])) continue;
                        if (isset($ref['isActive']) && $ref['isActive'] === false) continue;
                    ?>
                        <div class="flex-shrink-0 w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300 opacity-60 hover:opacity-100 aspect-[3/2]">
                            <img src="<?php echo url($ref['image']); ?>" alt="<?php echo $ref['name']; ?>" class="w-full h-full object-contain">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-primary-700 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-4">
                    <div class="w-12 h-12 mx-auto mb-4 bg-primary-600 rounded-full flex items-center justify-center">
                         <i data-lucide="users" class="w-6 h-6 text-primary-200"></i>
                    </div>
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-primary-200 text-sm font-medium">MUTLU MÜŞTERİ</div>
                </div>
                 <div class="p-4">
                    <div class="w-12 h-12 mx-auto mb-4 bg-primary-600 rounded-full flex items-center justify-center">
                         <i data-lucide="award" class="w-6 h-6 text-primary-200"></i>
                    </div>
                    <div class="text-4xl font-bold mb-2">15+</div>
                    <div class="text-primary-200 text-sm font-medium">YILLIK DENEYİM</div>
                </div>
                 <div class="p-4">
                     <div class="w-12 h-12 mx-auto mb-4 bg-primary-600 rounded-full flex items-center justify-center">
                         <i data-lucide="check-circle" class="w-6 h-6 text-primary-200"></i>
                    </div>
                    <div class="text-4xl font-bold mb-2">1000+</div>
                    <div class="text-primary-200 text-sm font-medium">TAMAMLANAN PROJE</div>
                </div>
                 <div class="p-4">
                    <div class="w-12 h-12 mx-auto mb-4 bg-primary-600 rounded-full flex items-center justify-center">
                         <i data-lucide="zap" class="w-6 h-6 text-primary-200"></i>
                    </div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-primary-200 text-sm font-medium">TEKNİK DESTEK</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-6">
                    Teknolojiye ve Geleceğe Yön Veren Firma
                </h2>
                <p class="text-lg text-secondary-600 mb-8 leading-relaxed">
                    Firmamız kurulduğu yıldan itibaren sürekli kendini geliştiren, yenilikleri takip eden
                    ve bu doğrultuda hizmet veren otomasyon çözüm firmasıdır. Müşteri memnuniyetini ilke
                    edinerek yolumuza devam etmekteyiz.
                </p>
                <a href="<?php echo url('/kurumsal/hakkimizda'); ?>" class="inline-flex items-center px-8 py-3 bg-primary-600 text-white font-bold rounded-lg hover:bg-primary-700 transition-colors">
                    Hakkımızda <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-4">
                    Sektörel Çözümlerimiz
                </h2>
                <?php $sectors = load_json('sectors'); ?>
                <p class="text-lg text-secondary-600 max-w-2xl mx-auto">
                    <?php echo count($sectors); ?> farklı sektörde uzmanlaşmış otomasyon çözümleri sunuyoruz
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                 <?php
                    $count = 0;
                    if(!empty($sectors)) {
                        foreach($sectors as $sector) {
                            if (isset($sector['isActive']) && $sector['isActive'] === false) continue;
                            if($count >= 6) break; // Limit to 6
                            ?>
                             <a href="<?php echo url('/cozumler/' . $sector['slug']); ?>">
                                <div class="group relative overflow-hidden rounded-xl hover:shadow-xl transition-all duration-300 cursor-pointer bg-white border border-gray-200">
                                    <div class="relative h-48 overflow-hidden">
                                        <?php if (!empty($sector['image'])): ?>
                                            <img src="<?php echo url($sector['image']); ?>" alt="<?php echo $sector['title']; ?>" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-300">
                                        <?php else: ?>
                                            <div class="flex items-center justify-center h-full bg-gray-100 text-gray-300">
                                                <i data-lucide="layers" class="w-16 h-16 opacity-50"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold mb-2 text-secondary-900 group-hover:text-primary-600 transition-colors">
                                            <?php echo $sector['title']; ?>
                                        </h3>
                                        <p class="text-sm text-secondary-600 line-clamp-3 mb-4 opacity-80">
                                            <?php echo $sector['description']; ?>
                                        </p>
                                        <div class="flex items-center text-primary-600 font-medium text-sm group-hover:gap-2 transition-all">
                                            <span>Detaylar</span>
                                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                            $count++;
                        }
                    }
                 ?>
            </div>
            <div class="text-center mt-12">
                <a href="<?php echo url('/cozumler'); ?>" class="inline-flex items-center px-8 py-3 border border-secondary-300 text-secondary-700 font-bold rounded-lg hover:bg-gray-50 transition-colors">
                    Tüm Çözümler <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <div class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
             <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-4">
                    Hizmetlerimiz
                </h2>
                <p class="text-lg text-secondary-600 max-w-2xl mx-auto">
                    Proje danışmanlığından devreye almaya kadar tam kapsamlı hizmet
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                 <?php
                    $services = load_json('services');
                    $limit = 6; // Show more services like next.js
                    $count = 0;
                    if(!empty($services)) {
                        foreach($services as $service) {
                            if (isset($service['isActive']) && $service['isActive'] === false) continue;
                            if($count >= $limit) break;
                            ?>
                             <a href="<?php echo url('/hizmetler/' . $service['slug']); ?>" class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full hover:-translate-y-1">
                                <div class="p-8 flex-1 flex flex-col">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-3"><?php echo $service['title']; ?></h3>
                                    <p class="text-gray-600 line-clamp-3 mb-6 flex-1 text-sm leading-relaxed"><?php echo isset($service['short_description']) ? $service['short_description'] : $service['description']; ?></p>
                                    <span class="text-primary-600 font-bold text-sm flex items-center group/btn mt-auto">
                                        İncele
                                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1"></i>
                                    </span>
                                </div>
                            </a>
                            <?php
                            $count++;
                        }
                    }
                 ?>
            </div>
             <div class="text-center mt-12">
                <a href="<?php echo url('/hizmetler'); ?>" class="inline-flex items-center justify-center px-8 py-3 border border-secondary-300 text-secondary-700 font-bold rounded-lg hover:bg-gray-100 transition-colors">
                    Tüm Hizmetler <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Global CTA -->
    <section class="py-20 bg-gradient-to-r from-primary-700 to-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-display font-bold mb-6">Projeniz İçin Hemen İletişime Geçin</h2>
            <p class="text-xl mb-8 text-primary-100 max-w-2xl mx-auto">Uzman ekibimiz size en uygun otomasyon çözümünü sunmak için hazır</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/iletisim">
                    <button class="inline-flex items-center justify-center rounded-lg font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 shadow-sm hover:opacity-90 active:opacity-100 px-6 py-3 text-lg bg-white text-primary-700 hover:bg-gray-100" style="background-color:var(--btn-primary-bg);color:var(--btn-primary-text)">
                        İletişime Geç
                    </button>
                </a>
                <a href="/hizmet-talebi">
                    <button class="inline-flex items-center justify-center rounded-lg font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:opacity-90 active:opacity-100 px-6 py-3 text-lg border-white text-white hover:bg-white/10 border" style="border-color:var(--primary-600)">
                        Teklif Al
                    </button>
                </a>
            </div>
        </div>
    </section>

</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('[data-slide-index]');
    const indicators = document.querySelectorAll('[data-indicator-index]');
    const totalSlides = slides.length;
    let slideInterval;

    function showSlide(index) {
        // Wrap around
        if (index >= totalSlides) index = 0;
        if (index < 0) index = totalSlides - 1;
        
        currentSlide = index;

        // Update Slides
        slides.forEach(slide => {
            slide.classList.remove('opacity-100', 'z-10');
            slide.classList.add('opacity-0', 'z-0');
            
            if (parseInt(slide.dataset.slideIndex) === currentSlide) {
                slide.classList.remove('opacity-0', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
                
                // Re-trigger animations
                const animatedElements = slide.querySelectorAll('.animate-fade-in-up');
                animatedElements.forEach(el => {
                    el.style.animation = 'none';
                    el.offsetHeight; /* trigger reflow */
                    el.style.animation = null; 
                });
            }
        });

        // Update Indicators
        indicators.forEach(ind => {
            if (parseInt(ind.dataset.indicatorIndex) === currentSlide) {
                ind.classList.remove('bg-white/50', 'w-3');
                ind.classList.add('bg-primary-500', 'w-8');
            } else {
                ind.classList.add('bg-white/50', 'w-3');
                ind.classList.remove('bg-primary-500', 'w-8');
            }
        });
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
        resetInterval();
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
        resetInterval();
    }

    function goToSlide(index) {
        showSlide(index);
        resetInterval();
    }

    function startInterval() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }

    // Init
    startInterval();
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0; /* Initially hidden */
    }
</style>

<?php
render_footer();
?>
