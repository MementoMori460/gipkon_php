<?php
// pages/admin/references.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$references = load_json('references');

// Handle Reordering
if (isset($_GET['action']) && (($_GET['action'] === 'move_up' || $_GET['action'] === 'move_down')) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $currentIndex = -1;
    foreach ($references as $index => $item) {
        if ($item['id'] == $id) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== -1) {
        if ($_GET['action'] === 'move_up' && $currentIndex > 0) {
            $temp = $references[$currentIndex];
            $references[$currentIndex] = $references[$currentIndex - 1];
            $references[$currentIndex - 1] = $temp;
        } elseif ($_GET['action'] === 'move_down' && $currentIndex < count($references) - 1) {
            $temp = $references[$currentIndex];
            $references[$currentIndex] = $references[$currentIndex + 1];
            $references[$currentIndex + 1] = $temp;
        }
        $references = array_values($references);
        save_json('references', $references);
    }
    header('Location: /admin/references');
    exit;
}

// Handle Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $references = array_filter($references, function($r) use ($id) {
        return $r['id'] != $id; // Loose comparison for string/int IDs
    });
    $references = array_values($references);
    save_json('references', $references);
    header('Location: /admin/references');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $isEdit = !empty($id);
    
    $newRef = [
        'id' => $isEdit ? $id : uniqid(),
        'name' => isset($_POST['name']) ? $_POST['name'] : '',
        'sector' => isset($_POST['sector']) ? $_POST['sector'] : '',
        'isActive' => isset($_POST['isActive']),
        'image' => isset($_POST['existing_image']) ? $_POST['existing_image'] : ''
    ];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $path = upload_file($_FILES['image'], 'references');
        if($path) $newRef['image'] = $path;
    }

    if ($isEdit) {
        foreach ($references as &$r) {
            if ($r['id'] == $id) {
                $r = array_merge($r, $newRef);
                break;
            }
        }
    } else {
        $references[] = $newRef;
    }

    if (save_json('references', $references)) {
        $success = 'Referans kaydedildi.';
        $references = load_json('references');
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editData = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($references as $r) {
        if ($r['id'] == $_GET['id']) $editData = $r;
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
                <?php echo ($action === 'list') ? 'Referans Yönetimi' : 'Referans Ekle/Düzenle'; ?>
            </h1>
            <?php if ($action === 'list'): ?>
                <a href="/admin/references?action=new" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Ekle
                </a>
            <?php else: ?>
                <a href="/admin/references" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Geri Dön</a>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Firma Adı</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sektör</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($references as $ref): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!isset($ref['isActive']) || $ref['isActive']): ?>
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
                                    <div class="h-10 w-16 flex items-center justify-center bg-gray-50 rounded border border-gray-100 p-1">
                                        <?php if (!empty($ref['image'])): ?>
                                            <img src="<?php echo $ref['image']; ?>" class="max-h-full max-w-full object-contain">
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 font-bold"><?php echo substr($ref['name'], 0, 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $ref['name']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo $ref['sector']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3 items-center">
                                         <div class="flex flex-col gap-1 mr-2">
                                            <a href="/admin/references?action=move_up&id=<?php echo $ref['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Yukarı Taşı">
                                                <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                            </a>
                                            <a href="/admin/references?action=move_down&id=<?php echo $ref['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Aşağı Taşı">
                                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                        <a href="/admin/references?action=edit&id=<?php echo $ref['id']; ?>" class="text-primary-600 hover:text-primary-900 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <a href="/admin/references?action=delete&id=<?php echo $ref['id']; ?>" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Bu referansı silmek istediğinize emin misiniz?');">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-xl">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Firma Adı</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editData['name']) ? $editData['name'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sektör</label>
                        <input type="text" name="sector" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editData['sector']) ? $editData['sector'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <?php if (!empty($editData['image'])): ?>
                            <img src="<?php echo $editData['image']; ?>" class="h-20 mb-2 rounded bg-gray-50 p-2 border">
                        <?php endif; ?>
                        <input type="file" name="image" class="w-full">
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
