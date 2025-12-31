<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']);
    $filePath = BASE_PATH . '/images/uploads/' . $fileToDelete;
    
    if (file_exists($filePath)) {
        unlink($filePath);
        header('Location: /admin/media?msg=deleted');
        exit;
    }
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploaded = upload_file($_FILES['file']);
    if ($uploaded) {
        header('Location: /admin/media?msg=uploaded');
        exit;
    }
}

// Scan Directory
$uploadsDir = BASE_PATH . '/images/uploads';
$files = [];
if (is_dir($uploadsDir)) {
    $scanned = scandir($uploadsDir);
    foreach ($scanned as $file) {
        if ($file === '.' || $file === '..') continue;
        if (preg_match('/\.(jpg|jpeg|png|gif|svg|webp)$/i', $file)) {
            $files[] = [
                'name' => $file,
                'path' => '/images/uploads/' . $file,
                'size' => filesize($uploadsDir . '/' . $file),
                'date' => filemtime($uploadsDir . '/' . $file)
            ];
        }
    }
}

// Sort by date desc
usort($files, function($a, $b) {
    return $b['date'] - $a['date'];
});

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Medya Kütüphanesi</h1>
            <button onclick="document.getElementById('uploadInput').click()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors flex items-center shadow-sm">
                <i data-lucide="upload" class="w-4 h-4 mr-2"></i> Dosya Yükle
            </button>
            <form id="uploadForm" method="POST" enctype="multipart/form-data" style="display:none;">
                <input type="file" id="uploadInput" name="file" onchange="document.getElementById('uploadForm').submit();">
            </form>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">İşlem başarıyla tamamlandı.</span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach ($files as $file): ?>
                <div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="aspect-square bg-gray-100 overflow-hidden relative">
                        <img src="<?php echo $file['path']; ?>" alt="<?php echo $file['name']; ?>" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="<?php echo $file['path']; ?>" target="_blank" class="p-2 bg-white rounded-full text-gray-700 hover:text-primary-600" title="Görüntüle">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="?delete=<?php echo urlencode($file['name']); ?>" onclick="return confirm('Bu görseli silmek istediğinize emin misiniz?')" class="p-2 bg-white rounded-full text-red-600 hover:text-red-700" title="Sil">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="text-xs text-gray-500 font-mono truncate" title="<?php echo $file['name']; ?>"><?php echo $file['name']; ?></p>
                        <p class="text-[10px] text-gray-400 mt-1"><?php echo round($file['size'] / 1024, 1); ?> KB</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
