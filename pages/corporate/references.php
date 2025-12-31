<?php
// pages/corporate/references.php
require_once __DIR__ . '/../../functions.php';
render_header();

$references = load_json('references');
?>

<main class="min-h-screen pb-20 bg-gray-50">
    <!-- Header Section -->
    <section class="bg-primary-900 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">
                Referanslarımız
            </h1>
            <p class="text-xl text-primary-200 max-w-2xl mx-auto">
                Bize güvenen ve birlikte başarıya ulaştığımız değerli iş ortaklarımız
            </p>
        </div>
    </section>

    <!-- References Grid -->
    <section class="container mx-auto px-4 -mt-10">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <?php if (!empty($references)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 md:gap-12">
                    <?php foreach ($references as $ref): 
                        if (isset($ref['isActive']) && $ref['isActive'] === false) continue;
                    ?>
                        <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl hover:shadow-md transition-shadow group border border-gray-100">
                            <!-- Image Container -->
                            <div class="relative w-64 h-32 mb-4 grayscale group-hover:grayscale-0 transition-all duration-300 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($ref['image'])): ?>
                                    <img 
                                        src="<?php echo $ref['image']; ?>" 
                                        alt="<?php echo $ref['name']; ?>" 
                                        class="object-contain px-2 max-h-full max-w-full"
                                    >
                                <?php else: ?>
                                    <!-- Fallback if no image -->
                                    <span class="text-gray-400 font-bold text-xl"><?php echo $ref['name']; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Name -->
                            <h3 class="text-lg font-semibold text-gray-900 text-center mb-1">
                                <?php echo $ref['name']; ?>
                            </h3>
                            
                            <!-- Sector Badge -->
                            <?php if (!empty($ref['sector'])): ?>
                                <span class="text-sm bg-primary-100/50 text-secondary-700 px-3 py-1 rounded-full">
                                    <?php echo $ref['sector']; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20">
                    <p class="text-gray-500 text-lg">Henüz referans eklenmemiş.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="container mx-auto px-4 mt-20 text-center">
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-6">
            Siz de Mutlu Müşterilerimiz Arasına Katılın
        </h2>
        <a href="/iletisim" class="inline-flex items-center px-8 py-4 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium text-lg">
            İletişime Geçin <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
        </a>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
