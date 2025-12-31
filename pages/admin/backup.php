<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

// Create Backup Logic
if (isset($_POST['action']) && $_POST['action'] === 'backup') {
    // Note: Recursive directory zip in PHP 5.6 without library is verbose.
    // For simplicity/compatibility, we will zip the 'data' folder only, or maybe copy it.
    // Actually, creating a .json dump of everything is safer and easier.
    
    $backupData = [
        'generated_at' => date('c'),
        'data' => []
    ];
    
    $files = ['projects', 'references', 'sectors', 'services', 'settings', 'menu', 'hero', 'faq', 'catalogs'];
    foreach ($files as $f) {
        $backupData['data'][$f] = load_json($f);
    }
    
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.json';
    $path = BASE_PATH . '/data/backups/';
    if (!is_dir($path)) mkdir($path, 0755, true);
    
    file_put_contents($path . $filename, json_encode($backupData, JSON_PRETTY_PRINT));
    
    // Update settings with last backup time
    $settings = load_json('settings');
    $settings['system']['lastBackup'] = date('c');
    save_json('settings', $settings);
    
    header('Location: /admin/backup?msg=created');
    exit;
}

// Restore Backup Logic
if (isset($_GET['restore'])) {
    $file = basename($_GET['restore']);
    $filepath = BASE_PATH . '/data/backups/' . $file;
    
    if (file_exists($filepath)) {
        $json = file_get_contents($filepath);
        $backup = json_decode($json, true);
        
        if (isset($backup['data']) && is_array($backup['data'])) {
            foreach ($backup['data'] as $key => $data) {
                // Save directly to live json files
                save_json($key, $data);
            }
            header('Location: /admin/backup?msg=restored');
            exit;
        } else {
            $error = "Yedek dosyası bozuk veya geçersiz format.";
        }
    } else {
        $error = "Yedek dosyası bulunamadı.";
    }
}

// Download Backup
if (isset($_GET['download'])) {
    $file = basename($_GET['download']);
    $filepath = BASE_PATH . '/data/backups/' . $file;
    if (file_exists($filepath)) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        readfile($filepath);
        exit;
    }
}

// List Backups
$backups = [];
$path = BASE_PATH . '/data/backups/';
if (is_dir($path)) {
    $files = scandir($path);
    foreach ($files as $f) {
        if (pathinfo($f, PATHINFO_EXTENSION) === 'json') {
            $backups[] = [
                'name' => $f,
                'size' => filesize($path . $f),
                'date' => filemtime($path . $f)
            ];
        }
    }
    // Sort Newest First
    usort($backups, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-8">Sistem & Yedekleme</h1>

        <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php if ($_GET['msg'] === 'created'): ?>
                    <span class="block sm:inline">Yedekleme başarıyla oluşturuldu.</span>
                <?php elseif ($_GET['msg'] === 'restored'): ?>
                    <span class="block sm:inline">Sistem başarıyla yedekten geri yüklendi.</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Create Backup -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-4 pb-2 border-b">Yeni Yedek Oluştur</h2>
                <p class="text-gray-600 mb-6">
                    Mevcut veritabanını (tüm JSON dosyaları) tek bir dosya olarak yedekler. 
                    Görseller dahil edilmez.
                </p>
                
                <form method="POST">
                    <input type="hidden" name="action" value="backup">
                    <button type="submit" class="w-full bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center">
                        <i data-lucide="database" class="w-5 h-5 mr-2"></i>
                        Yedek Oluştur
                    </button>
                </form>
            </div>

            <!-- Server Info -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-4 pb-2 border-b">Sunucu Bilgileri</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">PHP Sürümü</span>
                        <span class="font-mono text-primary-600"><?php echo phpversion(); ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">Sunucu Yazılımı</span>
                        <span class="text-gray-800 text-sm"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">İşletim Sistemi</span>
                        <span class="text-gray-800 text-sm"><?php echo php_uname('s') . ' ' . php_uname('r'); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Backup List -->
            <div class="col-span-1 lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-4 pb-2 border-b">Yedekleme Geçmişi</h2>
                
                <?php if (empty($backups)): ?>
                    <p class="text-gray-400 text-center py-8">Henüz yedek alınmamış.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="p-3 text-sm font-medium text-gray-500">Dosya Adı</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Tarih</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Boyut</th>
                                    <th class="p-3 text-sm font-medium text-gray-500 text-right">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($backups as $b): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-3 text-gray-800 font-mono text-sm"><?php echo $b['name']; ?></td>
                                        <td class="p-3 text-gray-600 text-sm"><?php echo date('d.m.Y H:i', $b['date']); ?></td>
                                        <td class="p-3 text-gray-600 text-sm"><?php echo round($b['size'] / 1024, 2); ?> KB</td>
                                        <td class="p-3 text-right flex justify-end gap-3">
                                            <a href="?restore=<?php echo $b['name']; ?>" class="inline-flex items-center text-amber-600 hover:text-amber-800 text-sm font-medium" onclick="return confirm('Bu yedeği geri yüklemek istediğinize emin misiniz? Mevcut verilerinizin üzerine yazılacaktır.');">
                                                <i data-lucide="rotate-ccw" class="w-4 h-4 mr-1"></i> Geri Yükle
                                            </a>
                                            <a href="?download=<?php echo $b['name']; ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 text-sm font-medium">
                                                <i data-lucide="download" class="w-4 h-4 mr-1"></i> İndir
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
