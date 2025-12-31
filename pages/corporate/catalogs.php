<?php
// pages/corporate/catalogs.php
require_once __DIR__ . '/../../functions.php';
render_header();

$catalogs = load_json('catalogs');
?>

<main class="min-h-screen">
    <!-- Hero -->
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-20 text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-4">
                E-Kataloglar
            </h1>
            <p class="text-primary-200 max-w-2xl mx-auto text-lg">
                Ürün ve çözümlerimiz hakkında detaylı bilgiye ulaşmak için kataloglarımızı inceleyebilirsiniz.
            </p>
        </div>
    </section>

    <!-- Catalogs Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <?php if (empty($catalogs)): ?>
                    <p class="text-center text-gray-500 w-full col-span-3">Henüz katalog eklenmemiş.</p>
                <?php else: ?>
                    <?php foreach ($catalogs as $catalog): ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="relative h-64 bg-gray-100 group-hover:bg-gray-200 transition-colors flex items-center justify-center p-8">
                                <!-- Catalog Cover -->
                                <?php if (!empty($catalog['image'])): ?>
                                    <div class="w-40 h-56 overflow-hidden shadow-lg transform group-hover:-translate-y-2 transition-transform duration-300">
                                        <img src="<?php echo $catalog['image']; ?>" alt="<?php echo $catalog['title']; ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="w-40 h-56 bg-white shadow-lg flex items-center justify-center transform group-hover:-translate-y-2 transition-transform duration-300">
                                        <div class="text-center p-4">
                                            <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i data-lucide="file-text" class="w-6 h-6 text-primary-600"></i>
                                            </div>
                                            <p class="text-xs text-gray-400 font-medium">ÖNİZLEME</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- File Type Badge -->
                                <div class="absolute top-4 right-4 bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-bold uppercase">
                                    PDF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-secondary-800 mb-2 group-hover:text-primary-700 transition-colors line-clamp-1">
                                    <?php echo $catalog['title']; ?>
                                </h3>
                                <p class="text-secondary-600 text-sm mb-4 min-h-[40px] line-clamp-2">
                                    <?php echo $catalog['description']; ?>
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-6">
                                    <span>Dosya Boyutu: <?php echo isset($catalog['size']) ? $catalog['size'] : '-'; ?></span>
                                    <span>Dil: <?php echo isset($catalog['language']) ? $catalog['language'] : 'TR'; ?></span>
                                </div>

                                <!-- Download Button -->
                                <?php if (!empty($catalog['file'])): ?>
                                    <a href="<?php echo $catalog['file']; ?>" target="_blank" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-colors group-hover:bg-primary-600 group-hover:text-white group-hover:border-primary-600">
                                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                        İndir / Görüntüle
                                    </a>
                                <?php else: ?>
                                    <button disabled class="w-full flex items-center justify-center px-4 py-2 border border-gray-200 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                        <i data-lucide="minus-circle" class="w-4 h-4 mr-2"></i>
                                        Dosya Yok
                                    </button>
                                <?php endif; ?>
                                <p class="text-xs text-center text-gray-400 mt-2">
                                    *PDF formatında indirilecektir
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Need More Info CTA -->
    <section class="py-16 bg-gray-50 text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-secondary-800 mb-4">
                Aradığınızı Bulamadınız mı?
            </h2>
            <p class="text-secondary-600 mb-8 max-w-xl mx-auto">
                Özel projeleriniz ve spesifik teknik doküman talepleriniz için bizimle iletişime geçebilirsiniz.
            </p>
            <a href="/iletisim" class="inline-flex items-center justify-center px-8 py-3 bg-white text-secondary-800 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                Bize Ulaşın <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
            </a>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
