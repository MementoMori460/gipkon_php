<?php
// pages/admin/video.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$videoData = load_json('video');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Main Video
    $videoData['main']['title'] = isset($_POST['title']) ? $_POST['title'] : '';
    $videoData['main']['description'] = isset($_POST['description']) ? $_POST['description'] : '';
    $videoData['main']['url'] = isset($_POST['url']) ? $_POST['url'] : '';

    // Highlights (Hardcoded index for simplicity in this form, or could be dynamic array)
    for($i=0; $i<3; $i++) {
        $videoData['highlights'][$i]['title'] = isset($_POST["h_title_$i"]) ? $_POST["h_title_$i"] : '';
        $videoData['highlights'][$i]['description'] = isset($_POST["h_desc_$i"]) ? $_POST["h_desc_$i"] : '';
    }

    if (save_json('video', $videoData)) {
        $success = 'Video ve içerik ayarları güncellendi.';
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-8">Video Sayfası Yönetimi</h1>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-4xl">
             <form method="POST" class="space-y-8">
                
                <!-- Main Video -->
                <div class="border-b border-gray-100 pb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="video" class="w-5 h-5 mr-2 text-primary-600"></i> Ana Video
                    </h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Video Başlığı</label>
                            <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($videoData['main']['title']) ? $videoData['main']['title'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık / Açıklama</label>
                            <input type="text" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($videoData['main']['description']) ? $videoData['main']['description'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Embed URL (Youtube/Vimeo)</label>
                            <input type="text" name="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm" value="<?php echo isset($videoData['main']['url']) ? $videoData['main']['url'] : ''; ?>" placeholder="https://www.youtube.com/embed/...">
                        </div>
                    </div>
                </div>

                <!-- Highlights -->
                <div>
                     <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="star" class="w-5 h-5 mr-2 text-primary-600"></i> Öne Çıkan Özellikler (Alt Kısım)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php for($i=0; $i<3; $i++): ?>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="font-bold text-sm text-gray-500 mb-3 text-center">Alan <?php echo $i+1; ?></h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Başlık</label>
                                        <input type="text" name="h_title_<?php echo $i; ?>" class="w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm" value="<?php echo isset($videoData['highlights'][$i]['title']) ? $videoData['highlights'][$i]['title'] : ''; ?>">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Açıklama</label>
                                        <textarea name="h_desc_<?php echo $i; ?>" rows="3" class="w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm"><?php echo isset($videoData['highlights'][$i]['description']) ? $videoData['highlights'][$i]['description'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Kaydet</button>
                </div>
             </form>
        </div>
    </main>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
