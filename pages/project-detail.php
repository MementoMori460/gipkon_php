<?php
// pages/project-detail.php
require_once __DIR__ . '/../functions.php';

// Get slug from query param (set in router)
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Load projects data (not loaded globally by default)
$projects = load_json('projects');

// Find project
$currentProject = null;
foreach ($projects as $p) {
    if ($p['slug'] === $slug) {
        $currentProject = $p;
        break;
    }
}

if (!$currentProject || (isset($currentProject['isActive']) && $currentProject['isActive'] === false)) {
    http_response_code(404);
    echo "Proje bulunamadı. <a href='/projeler'>Projelere Dön</a>";
    exit;
}

// Find related projects (same sector)
$relatedProjects = [];
foreach ($projects as $p) {
    if ($p['id'] !== $currentProject['id'] && $p['sector'] === $currentProject['sector']) {
        if (isset($p['isActive']) && $p['isActive'] === false) continue;
        $relatedProjects[] = $p;
        if (count($relatedProjects) >= 3) break;
    }
}
// If not enough related projects, fill with others
if (count($relatedProjects) < 3) {
    foreach ($projects as $p) {
        if ($p['id'] !== $currentProject['id'] && !in_array($p, $relatedProjects)) {
            if (isset($p['isActive']) && $p['isActive'] === false) continue;
            $relatedProjects[] = $p;
            if (count($relatedProjects) >= 3) break;
        }
    }
}

render_header();
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[50vh] min-h-[400px]">
        <?php if (!empty($currentProject['image'])): ?>
            <div class="absolute inset-0 z-0">
                 <div class="absolute inset-0 bg-gradient-to-r from-secondary-900/90 to-secondary-800/80 z-10"></div>
                <img
                    src="<?php echo $currentProject['image']; ?>"
                    alt="<?php echo $currentProject['title']; ?>"
                    class="w-full h-full object-cover object-center"
                />
            </div>
        <?php else: ?>
             <div class="absolute inset-0 bg-secondary-900 z-0"></div>
        <?php endif; ?>
        
        <div class="relative z-20 container mx-auto px-4 h-full flex flex-col justify-center">
            <span class="inline-block px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-full w-fit mb-4">
                <?php 
                // Find sector title
                $sectorTitle = $currentProject['sector'];
                foreach($sectors as $s) {
                    if($s['slug'] === $currentProject['sector']) {
                        $sectorTitle = $s['title'];
                        break;
                    }
                }
                echo $sectorTitle;
                ?>
            </span>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-white mb-6 animate-slide-up max-w-4xl">
                <?php echo $currentProject['title']; ?>
            </h1>
            <div class="flex flex-wrap items-center gap-6 text-gray-300 animate-fade-in">
                <?php if(!empty($currentProject['location'])): ?>
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary-400"></i>
                        <span><?php echo $currentProject['location']; ?></span>
                    </div>
                <?php endif; ?>
                <?php if(!empty($currentProject['year'])): ?>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 text-primary-400"></i>
                        <span><?php echo $currentProject['year']; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- About Project -->
                    <div class="prose prose-lg max-w-none text-secondary-600 mb-12">
                        <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                            Proje Hakkında
                        </h2>
                        <div class="text-lg leading-relaxed">
                            <?php 
                                // Show fullDescription if available, otherwise description
                                echo !empty($currentProject['fullDescription']) ? $currentProject['fullDescription'] : $currentProject['description']; 
                            ?>
                        </div>
                    </div>

                    <!-- Project Gallery -->
                    <?php if (!empty($currentProject['gallery']) && is_array($currentProject['gallery']) && count($currentProject['gallery']) > 0): ?>
                        <div class="mb-12">
                            <h3 class="text-2xl font-bold text-secondary-800 mb-6">Proje Galerisi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($currentProject['gallery'] as $img): ?>
                                    <div class="rounded-xl overflow-hidden shadow-sm h-64 group cursor-pointer">
                                        <img src="<?php echo $img; ?>" alt="Project Gallery" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Features -->
                    <?php if (!empty($currentProject['features'])): ?>
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-secondary-800 mb-6">Proje Özellikleri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($currentProject['features'] as $feature): ?>
                                <div class="flex items-start gap-3 bg-white p-4 rounded-lg shadow-sm">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-secondary-700 font-medium"><?php echo $feature; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Project Details Card -->
                    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-secondary-800 mb-6 pb-4 border-b border-gray-100">
                            Proje Detayları
                        </h3>
                        <div class="space-y-6">
                            <?php if(!empty($currentProject['client'])): ?>
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Müşteri</span>
                                    <span class="font-semibold text-secondary-900"><?php echo $currentProject['client']; ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($currentProject['location'])): ?>
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Lokasyon</span>
                                    <span class="font-semibold text-secondary-900"><?php echo $currentProject['location']; ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($currentProject['duration'])): ?>
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Süre</span>
                                    <span class="font-semibold text-secondary-900"><?php echo $currentProject['duration']; ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($currentProject['year'])): ?>
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Yıl</span>
                                    <span class="font-semibold text-secondary-900"><?php echo $currentProject['year']; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                     <!-- Technologies -->
                     <?php if (!empty($currentProject['technologies'])): ?>
                        <div class="bg-secondary-900 rounded-xl p-8 text-white relative overflow-hidden">
                             <!-- Decoration -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-600/20 rounded-full translate-x-1/2 -translate-y-1/2"></div>

                            <h3 class="text-xl font-bold mb-6 relative z-10">Kullanılan Teknolojiler</h3>
                            <div class="flex flex-wrap gap-2 relative z-10">
                                <?php foreach ($currentProject['technologies'] as $tech): ?>
                                    <span class="px-3 py-1 bg-white/10 rounded-lg text-sm font-medium border border-white/10 text-primary-100">
                                        <?php echo $tech; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Related Projects -->
                    <?php if(count($relatedProjects) > 0): ?>
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-secondary-800 mb-4">Benzer Projeler</h3>
                            <div class="space-y-4">
                                <?php foreach ($relatedProjects as $rp): ?>
                                    <a href="/projeler/<?php echo $rp['slug']; ?>" class="flex gap-4 group">
                                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
                                             <?php if (!empty($rp['image'])): ?>
                                                <img src="<?php echo $rp['image']; ?>" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-400"><i data-lucide="image"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-secondary-900 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                <?php echo $rp['title']; ?>
                                            </h4>
                                            <span class="text-xs text-gray-500 mt-1 block"><?php echo $rp['location']; ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-primary-600 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-display font-bold text-white mb-6">
                Sizin İçin Ne Yapabiliriz?
            </h2>
            <p class="text-primary-100 text-lg mb-8 max-w-2xl mx-auto">
                Benzer bir proje veya size özel otomasyon çözümleri için uzman ekibimizle iletişime geçin.
            </p>
            <div class="flex justify-center gap-4">
                <a href="/iletisim" class="px-8 py-3 bg-white text-primary-700 font-bold rounded-lg hover:bg-primary-50 transition-colors">
                    İletişime Geçin
                </a>
                <a href="/projeler" class="px-8 py-3 bg-primary-700 text-white font-bold rounded-lg hover:bg-primary-800 transition-colors border border-primary-500">
                    Tüm Projeler
                </a>
            </div>
        </div>
    </section>
</main>

<script>
    lucide.createIcons();
</script>

<?php render_footer(); ?>
