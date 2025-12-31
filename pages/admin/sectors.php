<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$sectors = load_json('sectors');

// Handle Reordering
if (isset($_GET['action']) && (($_GET['action'] === 'move_up' || $_GET['action'] === 'move_down')) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $currentIndex = -1;
    foreach ($sectors as $index => $item) {
        if ($item['id'] == $id) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== -1) {
        if ($_GET['action'] === 'move_up' && $currentIndex > 0) {
            $temp = $sectors[$currentIndex];
            $sectors[$currentIndex] = $sectors[$currentIndex - 1];
            $sectors[$currentIndex - 1] = $temp;
        } elseif ($_GET['action'] === 'move_down' && $currentIndex < count($sectors) - 1) {
            $temp = $sectors[$currentIndex];
            $sectors[$currentIndex] = $sectors[$currentIndex + 1];
            $sectors[$currentIndex + 1] = $temp;
        }
        $sectors = array_values($sectors);
        save_json('sectors', $sectors);
    }
    header('Location: /admin/sectors');
    exit;
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $sectors = array_filter($sectors, function($s) use ($idToDelete) {
        return $s['id'] !== $idToDelete;
    });
    $sectors = array_values($sectors); // Reindex
    save_json('sectors', $sectors);
    header('Location: /admin/sectors?msg=deleted');
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $isEdit = !empty($id);
    
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $slug = slugify($title);
    
    // Features processing
    $featuresStr = isset($_POST['features']) ? $_POST['features'] : '';
    $features = array_filter(array_map('trim', explode("\n", $featuresStr)));

    $newSector = [
        'id' => $isEdit ? $id : slugify($title), // Keep ID if edit, else generate from title
        'title' => $title,
        'slug' => $slug,
        'description' => isset($_POST['description']) ? $_POST['description'] : '',
        'image' => isset($_POST['existing_image']) ? $_POST['existing_image'] : '',
        'features' => array_values($features),
        'isActive' => isset($_POST['isActive']),
    ];

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploaded = upload_file($_FILES['image']);
        if ($uploaded) {
            $newSector['image'] = '/images/uploads/' . basename($uploaded);
        }
    }

    if ($isEdit) {
        foreach ($sectors as &$s) {
            if ($s['id'] === $id) {
                $s = array_merge($s, $newSector);
                break;
            }
        }
    } else {
        $sectors[] = $newSector;
    }

    save_json('sectors', $sectors);
    header('Location: /admin/sectors?msg=saved');
    exit;
}

// View Mode logic
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editData = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($sectors as $s) {
        if ($s['id'] === $_GET['id']) {
            $editData = $s;
            break;
        }
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Sektör Yönetimi</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=new" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Sektör
                </a>
            <?php else: ?>
                <a href="/admin/sectors" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Listeye Dön
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">İşlem başarıyla tamamlandı.</span>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left leading-4 text-gray-500 tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left leading-4 text-gray-500 tracking-wider">Görsel</th>
                            <th class="px-6 py-3 text-left leading-4 text-gray-500 tracking-wider">Başlık</th>
                            <th class="px-6 py-3 text-left leading-4 text-gray-500 tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-right leading-4 text-gray-500 tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($sectors as $sector): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!isset($sector['isActive']) || $sector['isActive']): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Yayında
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Pasif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (isset($sector['image']) && $sector['image']): ?>
                                    <img src="<?php echo $sector['image']; ?>" class="h-10 w-10 rounded object-cover" alt="">
                                <?php else: ?>
                                    <div class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                                        <i data-lucide="image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?php echo isset($sector['title']) ? $sector['title'] : ''; ?></td>
                            <td class="px-6 py-4 text-gray-500 text-sm"><?php echo isset($sector['slug']) ? $sector['slug'] : ''; ?></td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <div class="inline-flex flex-col gap-1 mr-2 align-middle">
                                    <a href="?action=move_up&id=<?php echo $sector['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Yukarı Taşı">
                                        <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                    </a>
                                    <a href="?action=move_down&id=<?php echo $sector['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Aşağı Taşı">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </a>
                                </div>
                                <a href="?action=edit&id=<?php echo $sector['id']; ?>" class="text-primary-600 hover:text-primary-900 transition-colors">Düzenle</a>
                                <a href="?delete=<?php echo $sector['id']; ?>" onclick="return confirm('Silmek istediğinize emin misiniz?')" class="text-red-600 hover:text-red-900 transition-colors">Sil</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: // Add/Edit Form ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo isset($editData['image']) ? $editData['image'] : ''; ?>">
                    <?php endif; ?>

                    <div class="flex items-center">
                         <?php 
                            $isActive = isset($editData['isActive']) ? $editData['isActive'] : true; 
                        ?>
                        <input type="checkbox" id="isActive" name="isActive" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" <?php echo $isActive ? 'checked' : ''; ?>>
                        <label for="isActive" class="ml-2 block text-sm text-gray-900">Yayında / Aktif</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sektör Başlığı</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" value="<?php echo isset($editData['title']) ? $editData['title'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"><?php echo isset($editData['description']) ? $editData['description'] : ''; ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Özellikler (Her satıra bir özellik)</label>
                        <textarea name="features" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="Hijyenik Tasarım&#10;Tam Otomasyon"><?php 
                            if (isset($editData['features']) && is_array($editData['features'])) {
                                echo implode("\n", $editData['features']);
                            }
                        ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Görsel</label>
                        <?php if (isset($editData['image']) && $editData['image']): ?>
                            <div class="mb-2">
                                <img src="<?php echo $editData['image']; ?>" alt="Preview" class="h-20 rounded-lg object-cover">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
