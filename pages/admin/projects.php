<?php
// pages/admin/projects.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$projects = load_json('projects');

// Handle Reordering
if (isset($_GET['action']) && (($_GET['action'] === 'move_up' || $_GET['action'] === 'move_down')) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $currentIndex = -1;
    foreach ($projects as $index => $item) {
        if ($item['id'] == $id) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== -1) {
        if ($_GET['action'] === 'move_up' && $currentIndex > 0) {
            $temp = $projects[$currentIndex];
            $projects[$currentIndex] = $projects[$currentIndex - 1];
            $projects[$currentIndex - 1] = $temp;
        } elseif ($_GET['action'] === 'move_down' && $currentIndex < count($projects) - 1) {
            $temp = $projects[$currentIndex];
            $projects[$currentIndex] = $projects[$currentIndex + 1];
            $projects[$currentIndex + 1] = $temp;
        }
        $projects = array_values($projects);
        save_json('projects', $projects);
    }
    header('Location: /admin/projects');
    exit;
}

// Handle Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $projects = array_filter($projects, function($p) use ($id) {
        return $p['id'] != $id;
    });
    $projects = array_values($projects);
    save_json('projects', $projects);
    header('Location: /admin/projects');
    exit;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $isEdit = !empty($id);
    
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    // Process features (comma separated)
    $featuresStr = isset($_POST['features']) ? $_POST['features'] : '';
    $features = array_map('trim', explode(',', $featuresStr));
    
    $newProject = [
        'id' => $isEdit ? $id : uniqid(),
        'title' => $title,
        'slug' => slugify($title),
        'description' => isset($_POST['description']) ? $_POST['description'] : '',
        'fullDescription' => isset($_POST['fullDescription']) ? $_POST['fullDescription'] : '',
        'sector' => isset($_POST['sector']) ? $_POST['sector'] : '',
        'year' => isset($_POST['year']) ? $_POST['year'] : date('Y'),
        'features' => $features,
        'technologies' => isset($_POST['technologies']) ? array_map('trim', explode(',', $_POST['technologies'])) : [],
        'isActive' => isset($_POST['isActive']),
        'image' => isset($_POST['existing_image']) ? $_POST['existing_image'] : ''
    ];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $path = upload_file($_FILES['image'], 'projects');
        if ($path) $newProject['image'] = $path;
    }

    if ($isEdit) {
        foreach ($projects as &$p) {
            if ($p['id'] == $id) {
                $p = array_merge($p, $newProject);
                break;
            }
        }
    } else {
        $projects[] = $newProject;
    }

    if (save_json('projects', $projects)) {
        $success = 'Proje başarıyla kaydedildi.';
        $projects = load_json('projects');
    }
}

// View Mode
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editData = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($projects as $p) {
        if ($p['id'] == $_GET['id']) $editData = $p;
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
                <?php echo ($action === 'list') ? 'Proje Yönetimi' : (($action === 'edit') ? 'Proje Düzenle' : 'Yeni Proje'); ?>
            </h1>
            <?php if ($action === 'list'): ?>
                <a href="/admin/projects?action=new" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Ekle
                </a>
            <?php else: ?>
                <a href="/admin/projects" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Durum</th>
                            <th class="px-6 py-3 text-left">Görsel</th>
                            <th class="px-6 py-3 text-left">Proje Adı</th>
                            <th class="px-6 py-3 text-left">Sektör</th>
                            <th class="px-6 py-3 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!isset($project['isActive']) || $project['isActive']): ?>
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
                                     <?php if (!empty($project['image'])): ?>
                                        <img src="<?php echo $project['image']; ?>" class="h-10 w-10 rounded object-cover">
                                    <?php else: ?>
                                        <div class="h-10 w-10 bg-gray-100 rounded"></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-medium"><?php echo $project['title']; ?></td>
                                <td class="px-6 py-4 text-gray-500">
                                    <?php 
                                        // Find sector name from ID/Slug
                                        foreach($sectors as $s) if($s['slug'] == $project['sector']) echo $s['title']; 
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="flex flex-col gap-1 mr-2">
                                            <a href="/admin/projects?action=move_up&id=<?php echo $project['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Yukarı Taşı">
                                                <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                            </a>
                                            <a href="/admin/projects?action=move_down&id=<?php echo $project['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Aşağı Taşı">
                                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                        <a href="/admin/projects?action=edit&id=<?php echo $project['id']; ?>" class="text-primary-600 mr-4">Düzenle</a>
                                        <a href="/admin/projects?action=delete&id=<?php echo $project['id']; ?>" class="text-red-600" onclick="return confirm('Sil?');">Sil</a>
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
                    <?php endif; ?>

                    <div class="flex items-center">
                        <?php 
                            $isActive = isset($editData['isActive']) ? $editData['isActive'] : true; 
                        ?>
                        <input id="isActive" name="isActive" type="checkbox" <?php echo $isActive ? 'checked' : ''; ?> class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="isActive" class="ml-2 block text-sm text-gray-900">
                            Yayında / Aktif
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proje Adı</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editData['title']) ? $editData['title'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sektör</label>
                        <select name="sector" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Seçiniz</option>
                            <?php foreach ($sectors as $s): ?>
                                <option value="<?php echo $s['slug']; ?>" <?php echo (isset($editData['sector']) && $editData['sector'] === $s['slug']) ? 'selected' : ''; ?>>
                                    <?php echo $s['title']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Yıl</label>
                        <input type="text" name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editData['year']) ? $editData['year'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama (Özet/Liste Görünümü)</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo isset($editData['description']) ? $editData['description'] : ''; ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detaylı Açıklama (Proje Sayfası)</label>
                        <textarea name="fullDescription" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo isset($editData['fullDescription']) ? $editData['fullDescription'] : ''; ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Özellikler (Virgül ile ayırın)</label>
                        <input type="text" name="features" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Tam Otomasyon, 7/24 Destek..." value="<?php echo isset($editData['features']) ? implode(', ', $editData['features']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teknolojiler (Virgül ile ayırın)</label>
                        <input type="text" name="technologies" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="PLC, SCADA, SQL..." value="<?php echo (isset($editData['technologies']) && is_array($editData['technologies'])) ? implode(', ', $editData['technologies']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Görsel</label>
                        <?php if (!empty($editData['image'])): ?>
                            <img src="<?php echo $editData['image']; ?>" class="h-20 mb-2 rounded">
                        <?php endif; ?>
                        <input type="file" name="image" class="w-full">
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Kaydet</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
