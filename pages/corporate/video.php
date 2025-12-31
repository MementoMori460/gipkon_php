<?php
// pages/corporate/video.php
require_once __DIR__ . '/../../functions.php';
render_header();
?>

<main class="min-h-screen">
    <!-- Hero -->
    <section class="bg-gray-900 text-white py-16 text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-display font-bold mb-4">Tanıtım Videomuz</h1>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Teknoloji ve inovasyon dolu dünyamızı keşfedin.
            </p>
        </div>
    </section>

    <!-- Main Video Section -->
    <!-- Main Video Section -->
    <?php $videoData = load_json('video'); ?>
    <section class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="aspect-w-16 aspect-h-9 bg-gray-800 rounded-2xl overflow-hidden shadow-2xl relative group h-[500px]">
                    <?php if (!empty($videoData['main']['url'])): ?>
                         <iframe width="100%" height="100%" src="<?php echo $videoData['main']['url']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php else: ?>
                        <div class="absolute inset-0 flex items-center justify-center bg-gray-800">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform cursor-pointer shadow-glow">
                                    <i data-lucide="play" class="w-8 h-8 text-white ml-1"></i>
                                </div>
                                <p class="text-gray-400 font-medium"><?php echo isset($videoData['main']['title']) ? $videoData['main']['title'] : 'Kurumsal Tanıtım Filmi'; ?></p>
                                <p class="text-xs text-gray-500 mt-2"><?php echo isset($videoData['main']['description']) ? $videoData['main']['description'] : ''; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlights Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-6xl mx-auto">
                <?php 
                $highlights = isset($videoData['highlights']) ? $videoData['highlights'] : [];
                foreach ($highlights as $h): ?>
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-secondary-800 mb-4"><?php echo $h['title']; ?></h3>
                        <p class="text-secondary-600">
                            <?php echo $h['description']; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gray-50 text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-secondary-800 mb-6">Daha Fazla Bilgi Alın</h2>
            <a href="/iletisim" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors bg-primary-600 text-white hover:bg-primary-700 h-11 px-8">
                Bizimle İletişime Geçin <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
            </a>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
