<?php
// pages/admin/catalogs.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$catalogs = load_json('catalogs');

// Handle Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $catalogs = array_filter($catalogs, function($c) use ($id) {
        return $c['id'] != $id;
    });
    $catalogs = array_values($catalogs);
    save_json('catalogs', $catalogs);
    header('Location: /admin/catalogs');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $isEdit = !empty($id);
    
    $newCatalog = [
        'id' => $isEdit ? $id : uniqid(),
        'title' => isset($_POST['title']) ? $_POST['title'] : '',
        'description' => isset($_POST['description']) ? $_POST['description'] : '',
        'image' => isset($_POST['existing_image']) ? $_POST['existing_image'] : '',
        'file' => isset($_POST['existing_file']) ? $_POST['existing_file'] : '',
        'size' => isset($_POST['size']) ? $_POST['size'] : '',
        'language' => isset($_POST['language']) ? $_POST['language'] : 'TR'
    ];

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $path = upload_file($_FILES['image'], 'catalogs');
        if($path) $newCatalog['image'] = $path;
    }

    // PDF Upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $path = upload_file($_FILES['file'], 'downloads');
        if($path) {
            $newCatalog['file'] = $path;
            
            // Auto-calculate size if possible
            $filesize = $_FILES['file']['size'];
            if ($filesize > 1024 * 1024) {
                $newCatalog['size'] = round($filesize / (1024 * 1024), 1) . ' MB';
            } else {
                $newCatalog['size'] = round($filesize / 1024, 0) . ' KB';
            }
        }
    }

    if ($isEdit) {
        foreach ($catalogs as &$c) {
            if ($c['id'] == $id) {
                $c = array_merge($c, $newCatalog);
                break;
            }
        }
    } else {
        $catalogs[] = $newCatalog;
    }

    if (save_json('catalogs', $catalogs)) {
        $success = 'Katalog kaydedildi.';
        $catalogs = load_json('catalogs');
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editData = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($catalogs as $c) {
        if ($c['id'] == $_GET['id']) $editData = $c;
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                <?php echo ($action === 'list') ? 'Katalog Yönetimi' : 'Katalog Ekle/Düzenle'; ?>
            </h1>
            <?php if ($action === 'list'): ?>
                <a href="/admin/catalogs?action=new" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Ekle
                </a>
            <?php else: ?>
                <a href="/admin/catalogs" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Geri Dön</a>
            <?php endif; ?>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
             <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapak</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dil</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosya</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($catalogs as $cat): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-16 w-12 bg-gray-100 rounded border border-gray-200 overflow-hidden flex items-center justify-center">
                                        <?php if (!empty($cat['image'])): ?>
                                            <img src="<?php echo $cat['image']; ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i data-lucide="file-text" class="text-gray-400"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $cat['title']; ?></div>
                                    <div class="text-xs text-secondary-500 line-clamp-1"><?php echo $cat['description']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo isset($cat['language']) ? $cat['language'] : 'TR'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if (!empty($cat['file'])): ?>
                                        <a href="<?php echo $cat['file']; ?>" target="_blank" class="text-primary-600 hover:underline flex items-center">
                                            <i data-lucide="download" class="w-3 h-3 mr-1"></i> PDF (<?php echo isset($cat['size']) ? $cat['size'] : '?'; ?>)
                                        </a>
                                    <?php else: ?>
                                        <span class="text-red-400">Dosya Yok</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="/admin/catalogs?action=edit&id=<?php echo $cat['id']; ?>" class="text-primary-600 hover:text-primary-900 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <a href="/admin/catalogs?action=delete&id=<?php echo $cat['id']; ?>" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Bu kataloğu silmek istediğinize emin misiniz?');">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                 <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo isset($editData['image']) ? $editData['image'] : ''; ?>">
                        <input type="hidden" name="existing_file" value="<?php echo isset($editData['file']) ? $editData['file'] : ''; ?>">
                        <input type="hidden" name="size" value="<?php echo isset($editData['size']) ? $editData['size'] : ''; ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editData['title']) ? $editData['title'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo isset($editData['description']) ? $editData['description'] : ''; ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dil</label>
                            <select name="language" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="TR" <?php echo (isset($editData['language']) && $editData['language'] === 'TR') ? 'selected' : ''; ?>>Türkçe (TR)</option>
                                <option value="EN" <?php echo (isset($editData['language']) && $editData['language'] === 'EN') ? 'selected' : ''; ?>>İngilizce (EN)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kapak Görseli</label>
                            <?php if (!empty($editData['image'])): ?>
                                <img src="<?php echo $editData['image']; ?>" class="h-24 mb-2 rounded bg-gray-50 border object-cover">
                            <?php endif; ?>
                            <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Katalog Dosyası (PDF)</label>
                            <?php if (!empty($editData['file'])): ?>
                                <div class="mb-2 text-sm text-green-600 flex items-center">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i> Dosya Yüklü
                                </div>
                            <?php endif; ?>
                            <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Kaydet</button>
                    </div>
                 </form>
            </div>
        <?php endif; ?>
    </main>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
