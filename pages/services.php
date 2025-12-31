<?php
// pages/services.php
require_once __DIR__ . '/../functions.php';
render_header();
?>

<main class="min-h-screen">
    <!-- Header -->
    <section class="bg-primary-700 text-white py-12 md:py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-6">Hizmetlerimiz</h1>
            <p class="text-lg md:text-xl text-primary-100 max-w-2xl mx-auto">
                Endüstriyel otomasyon ihtiyaçlarınız için kapsamlı çözümler
            </p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4">
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($services as $service): 
                    if (isset($service['isActive']) && $service['isActive'] === false) continue;
                ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                        <div class="h-48 overflow-hidden bg-gray-100 flex items-center justify-center border-b border-gray-100">
                            <?php if (!empty($service['image'])): ?>
                                <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['title']; ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="text-gray-300">
                                    <i data-lucide="image" class="w-12 h-12 opacity-50"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-secondary-800 mb-2"><?php echo $service['title']; ?></h3>
                            <p class="text-secondary-600 text-sm mb-4 line-clamp-3">
                                <?php echo $service['description']; ?>
                            </p>
                            <a href="/hizmetler/<?php echo $service['slug']; ?>" class="mt-auto text-primary-600 font-medium hover:text-primary-700 flex items-center gap-1">
                                Detayları İncele <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
                    Kapsamlı Hizmet Anlayışı
                </h2>
                <p class="text-lg text-secondary-600">
                    Projenizin her aşamasında yanınızdayız. İhtiyaç analizinden başlayarak,
                    tasarım, kurulum, devreye alma ve sonrasında 7/24 teknik destek ile
                    hizmetinizdeyiz.
                </p>
            </div>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
