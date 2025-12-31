<?php
// pages/admin/services.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$error = '';
$services = load_json('services');

// Handle Reordering
if (isset($_GET['action']) && (($_GET['action'] === 'move_up' || $_GET['action'] === 'move_down')) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $currentIndex = -1;
    foreach ($services as $index => $item) {
        if ($item['id'] == $id) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== -1) {
        if ($_GET['action'] === 'move_up' && $currentIndex > 0) {
            $temp = $services[$currentIndex];
            $services[$currentIndex] = $services[$currentIndex - 1];
            $services[$currentIndex - 1] = $temp;
        } elseif ($_GET['action'] === 'move_down' && $currentIndex < count($services) - 1) {
            $temp = $services[$currentIndex];
            $services[$currentIndex] = $services[$currentIndex + 1];
            $services[$currentIndex + 1] = $temp;
        }
        $services = array_values($services);
        save_json('services', $services);
    }
    header('Location: /admin/services');
    exit;
}

// Handle Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = $_GET['id'];
    $services = array_filter($services, function($s) use ($idToDelete) {
        return $s['id'] != $idToDelete;
    });
    // Re-index array
    $services = array_values($services);
    save_json('services', $services);
    header('Location: /admin/services');
    exit;
}

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $isEdit = !empty($id);
    
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    // Generate slug from title if not provided or empty
    $slug = slugify($title);
    
    $newService = [
        'id' => $isEdit ? $id : uniqid(),
        'title' => $title,
        'slug' => $slug,
        'description' => $description,
        'icon' => isset($_POST['icon']) ? $_POST['icon'] : 'box',
        'isActive' => isset($_POST['isActive']), // Checkbox sends value only if checked
        // Retain existing image if no new file uploaded
        'image' => isset($_POST['existing_image']) ? $_POST['existing_image'] : ''
    ];

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadedPath = upload_file($_FILES['image'], 'services');
        if ($uploadedPath) {
            $newService['image'] = $uploadedPath;
        }
    }

    if ($isEdit) {
        foreach ($services as &$s) {
            if ($s['id'] == $id) {
                $s = array_merge($s, $newService);
                break;
            }
        }
    } else {
        $services[] = $newService;
    }

    if (save_json('services', $services)) {
        $success = 'Hizmet başarıyla kaydedildi.';
        // Refresh data
        $services = load_json('services');
    } else {
        $error = 'Kaydedilirken bir hata oluştu.';
    }
}

// Determine View Mode
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editService = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($services as $s) {
        if ($s['id'] == $_GET['id']) {
            $editService = $s;
            break;
        }
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                <?php echo ($action === 'list') ? 'Hizmet Yönetimi' : (($action === 'edit') ? 'Hizmet Düzenle' : 'Yeni Hizmet Ekle'); ?>
            </h1>
            <?php if ($action === 'list'): ?>
                <a href="/admin/services?action=new" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Ekle
                </a>
            <?php else: ?>
                <a href="/admin/services" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Geri Dön
                </a>
            <?php endif; ?>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <!-- List View -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Görsel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Açıklama</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!isset($service['isActive']) || $service['isActive']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Yayında
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Pasif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!empty($service['image'])): ?>
                                        <img src="<?php echo $service['image']; ?>" class="h-10 w-10 rounded object-cover">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center">
                                            <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $service['title']; ?></div>
                                    <div class="text-xs text-gray-500">/<?php echo $service['slug']; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 line-clamp-2"><?php echo $service['description']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="flex flex-col gap-1 mr-2">
                                            <a href="/admin/services?action=move_up&id=<?php echo $service['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Yukarı Taşı">
                                                <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                            </a>
                                            <a href="/admin/services?action=move_down&id=<?php echo $service['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Aşağı Taşı">
                                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                        <a href="/admin/services?action=edit&id=<?php echo $service['id']; ?>" class="text-primary-600 hover:text-primary-900">Düzenle</a>
                                        <a href="/admin/services?action=delete&id=<?php echo $service['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <!-- Add/Edit Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php if ($editService): ?>
                        <input type="hidden" name="id" value="<?php echo $editService['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo isset($editService['image']) ? $editService['image'] : ''; ?>">
                    <?php endif; ?>

                     <div class="flex items-center">
                        <?php 
                            $isActive = isset($editService['isActive']) ? $editService['isActive'] : true; 
                        ?>
                        <input id="isActive" name="isActive" type="checkbox" <?php echo $isActive ? 'checked' : ''; ?> class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="isActive" class="ml-2 block text-sm text-gray-900">
                            Yayında / Aktif
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hizmet Başlığı</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" value="<?php echo isset($editService['title']) ? $editService['title'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kısa Açıklama</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"><?php echo isset($editService['description']) ? $editService['description'] : ''; ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İkon (Lucide Icon Name)</label>
                        <input type="text" name="icon" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" value="<?php echo isset($editService['icon']) ? $editService['icon'] : 'box'; ?>">
                        <p class="text-xs text-gray-500 mt-1">Örn: box, settings, wrench, cpu</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Görsel Yükle</label>
                        <?php if (!empty($editService['image'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo $editService['image']; ?>" class="h-20 rounded border border-gray-200">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*" class="w-full">
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
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
