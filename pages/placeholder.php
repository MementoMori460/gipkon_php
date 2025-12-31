<?php
require_once __DIR__ . '/../../functions.php';
render_header();
$pageTitle = isset($pageTitle) ? $pageTitle : 'Sayfa Yapım Aşamasında';
?>

<main class="min-h-screen">
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-20">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-display font-bold mb-6"><?php echo $pageTitle; ?></h1>
        </div>
    </section>

    <section class="py-20 text-center">
        <div class="container mx-auto px-4">
             <i data-lucide="cone" class="w-16 h-16 mx-auto mb-6 text-yellow-500"></i>
             <p class="text-xl text-gray-600">Bu sayfa şu anda hazırlanmaktadır. Lütfen daha sonra tekrar ziyaret ediniz.</p>
             <a href="/" class="inline-block mt-8 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Anasayfaya Dön</a>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
