<?php
// pages/projects.php
require_once __DIR__ . '/../functions.php';
render_header();

$projects = load_json('projects');
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-secondary-900 text-white py-12 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-secondary-900 via-secondary-800 to-secondary-900 opacity-90 z-0"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-display font-bold mb-6">
                Projelerimiz
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
                Farklı sektörlerde başarıyla tamamladığımız otomasyon projelerimizden örnekler
            </p>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4">
            <!-- Sector Filter Buttons (Visual Only) -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button class="px-6 py-2 rounded-full bg-primary-600 text-white font-medium shadow-sm">
                    Tümü
                </button>
                <?php 
                    $activeSectors = array_filter($sectors, function($s) {
                        return !isset($s['isActive']) || $s['isActive'] !== false;
                    });
                    foreach (array_slice($activeSectors, 0, 5) as $sector): 
                ?>
                    <a href="/cozumler/<?php echo $sector['slug']; ?>" class="px-6 py-2 rounded-full bg-white border border-gray-200 text-secondary-600 hover:bg-gray-50 transition-colors">
                        <?php echo $sector['title']; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projects as $project): 
                      if (isset($project['isActive']) && $project['isActive'] === false) continue;
                      // Find sector title
                      $projectSectorTitle = "Endüstriyel Otomasyon";
                      foreach ($sectors as $s) {
                          if ($s['slug'] === $project['sector']) {
                              $projectSectorTitle = $s['title'];
                              break;
                          }
                      }
                ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all">
                        <div class="relative h-48 bg-gray-200">
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                Project Image
                            </div>
                            <?php if (!empty($project['image'])): ?>
                                <img src="<?php echo $project['image']; ?>" alt="<?php echo $project['title']; ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php endif; ?>
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-primary-700">
                                <?php echo $project['year']; ?>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-xs font-medium text-primary-600 mb-2 uppercase tracking-wide">
                                <?php echo $projectSectorTitle; ?>
                            </div>
                            <h3 class="text-xl font-bold text-secondary-800 mb-3 group-hover:text-primary-700 transition-colors">
                                <?php echo $project['title']; ?>
                            </h3>
                            <p class="text-secondary-600 text-sm mb-4 line-clamp-3">
                                <?php echo $project['description']; ?>
                            </p>
                            <div class="space-y-2 mb-6">
                                <?php foreach (array_slice($project['features'], 0, 2) as $feature): ?>
                                    <div class="flex items-center text-xs text-secondary-500">
                                        <div class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2"></div>
                                        <?php echo $feature; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <a href="/projeler/<?php echo isset($project['slug']) ? $project['slug'] : $project['id']; ?>" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-colors">
                                Detaylı İncele <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary-700 text-white text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-display font-bold mb-6">
                Sizin Projenizi de Hayata Geçirelim
            </h2>
            <p class="text-lg text-primary-100 mb-8 max-w-2xl mx-auto">
                Uzman ekibimizle tanışın, endüstriyel otomasyon ihtiyaçlarınıza en uygun çözümü birlikte planlayalım.
            </p>
            <a href="/iletisim" class="inline-block px-8 py-3 bg-white text-primary-700 font-bold rounded-lg hover:bg-gray-100 transition-colors">
                Proje Başlat
            </a>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
