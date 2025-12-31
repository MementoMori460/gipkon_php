<?php
// pages/admin/dashboard.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

// Calculate Stats
$projects = load_json('projects');
$projectCount = count($projects);

$references = load_json('references');
$refCount = count($references);

$sectors = load_json('sectors');
$totalSectors = count($sectors);
$activeSectors = 0;
foreach ($sectors as $s) {
    // PHP 5.6 Compat: Check isset explicitly
    if (!isset($s['isActive']) || $s['isActive'] !== false) {
        $activeSectors++;
    }
}

// Last Modified Calculation
$lastModified = 0;
$filesToCheck = ['projects', 'references', 'sectors', 'services', 'settings'];
foreach ($filesToCheck as $f) {
    $path = BASE_PATH . '/data/' . $f . '.json';
    if (file_exists($path)) {
        $mtime = filemtime($path);
        if ($mtime > $lastModified) {
            $lastModified = $mtime;
        }
    }
}
$lastUpdate = $lastModified > 0 ? date('d.m.Y H:i', $lastModified) : '-';

// Last Backup
$settings = load_json('settings');
// PHP 5.6 compatible access
$lastBackup = isset($settings['system']['lastBackup']) ? date('d.m.Y H:i', strtotime($settings['system']['lastBackup'])) : 'Hiç Alınmadı';

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Genel Bakış</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Projects Stats -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">Toplam Proje</h3>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <i data-lucide="briefcase" class="w-5 h-5 text-primary-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-primary-600"><?php echo $projectCount; ?></div>
                <div class="text-xs text-green-500 mt-2 font-medium">Yayında</div>
            </div>

            <!-- Services Stats (New) -->
            <?php 
            $services = load_json('services');
            $serviceCount = count($services);
            ?>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">Hizmetler</h3>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <i data-lucide="box" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800"><?php echo $serviceCount; ?></div>
                <div class="text-xs text-blue-500 mt-2 font-medium">Aktif</div>
            </div>

            <!-- Sectors Stats -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">Sektörler</h3>
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <i data-lucide="layers" class="w-5 h-5 text-purple-600"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $activeSectors; ?></span>
                    <span class="text-sm text-gray-400">/ <?php echo $totalSectors; ?></span>
                </div>
                <div class="text-xs text-green-500 mt-2 font-medium">Aktif / Toplam</div>
            </div>

            <!-- References Stats -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">Referanslar</h3>
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <i data-lucide="users" class="w-5 h-5 text-amber-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800"><?php echo $refCount; ?></div>
                <div class="text-xs text-green-500 mt-2 font-medium">Görüntüleniyor</div>
            </div>

            <!-- Catalogs Stats (New) -->
            <?php 
            $catalogs = load_json('catalogs');
            $catalogCount = count($catalogs);
            ?>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">Kataloglar</h3>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <i data-lucide="file-text" class="w-5 h-5 text-red-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800"><?php echo $catalogCount; ?></div>
                <div class="text-xs text-gray-500 mt-2 font-medium">Dosya</div>
            </div>

            <!-- FAQ Stats (New) -->
            <?php 
            $faq = load_json('faq');
            $faqCount = count($faq);
            ?>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">S.S.S.</h3>
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <i data-lucide="help-circle" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800"><?php echo $faqCount; ?></div>
                <div class="text-xs text-gray-500 mt-2 font-medium">Soru-Cevap</div>
            </div>

            <!-- System Stats -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-500 text-sm font-medium">Son Güncelleme</h3>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <i data-lucide="clock" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
                <div class="text-lg font-semibold text-gray-800"><?php echo $lastUpdate; ?></div>
                <div class="text-xs text-gray-400 mt-2">Son Yedek: <?php echo $lastBackup; ?></div>
            </div>
            
             <!-- DB Action (Visual filler for now) -->
             <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center hover:bg-gray-50 transition-colors cursor-pointer group" onclick="window.location.href='/admin/settings'">
                <div class="p-3 bg-gray-100 rounded-full mb-3 group-hover:bg-primary-100 transition-colors">
                    <i data-lucide="settings" class="w-6 h-6 text-gray-600 group-hover:text-primary-600"></i>
                </div>
                <span class="text-sm font-bold text-gray-700 group-hover:text-primary-700">Hızlı Ayarlar</span>
            </div>



        <!-- System Version Info -->
        <div class="mt-8 pt-8 border-t border-gray-200 text-center text-xs text-gray-400">
            <p>Gipkon CMS v<?php echo get_system_version(); ?></p>
        </div>
    </main>
</div>

<script>lucide.createIcons();</script>

</body>
</html>
