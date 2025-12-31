<?php
// pages/admin/faq.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$faq = load_json('faq');

// Handle Reordering
if (isset($_GET['action']) && (($_GET['action'] === 'move_up' || $_GET['action'] === 'move_down')) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $currentIndex = -1;
    foreach ($faq as $index => $item) {
        if ($item['id'] == $id) {
            $currentIndex = $index;
            break;
        }
    }

    if ($currentIndex !== -1) {
        if ($_GET['action'] === 'move_up' && $currentIndex > 0) {
            $temp = $faq[$currentIndex];
            $faq[$currentIndex] = $faq[$currentIndex - 1];
            $faq[$currentIndex - 1] = $temp;
        } elseif ($_GET['action'] === 'move_down' && $currentIndex < count($faq) - 1) {
            $temp = $faq[$currentIndex];
            $faq[$currentIndex] = $faq[$currentIndex + 1];
            $faq[$currentIndex + 1] = $temp;
        }
        $faq = array_values($faq);
        save_json('faq', $faq);
    }
    header('Location: /admin/faq');
    exit;
}

// Handle Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $faq = array_filter($faq, function($item) use ($id) {
        return $item['id'] != $id;
    });
    $faq = array_values($faq);
    save_json('faq', $faq);
    header('Location: /admin/faq');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $isEdit = !empty($id);
    
    $newItem = [
        'id' => $isEdit ? $id : uniqid(),
        'question' => isset($_POST['question']) ? $_POST['question'] : '',
        'answer' => isset($_POST['answer']) ? $_POST['answer'] : '',
        'category' => isset($_POST['category']) ? $_POST['category'] : 'Genel'
    ];

    if ($isEdit) {
        foreach ($faq as &$item) {
            if ($item['id'] == $id) {
                $item = array_merge($item, $newItem);
                break;
            }
        }
    } else {
        $faq[] = $newItem;
    }

    if (save_json('faq', $faq)) {
        $success = 'Soru kaydedildi.';
        $faq = load_json('faq');
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editData = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($faq as $item) {
        if ($item['id'] == $_GET['id']) $editData = $item;
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
                <?php echo ($action === 'list') ? 'S.S.S. Yönetimi' : 'Soru Ekle/Düzenle'; ?>
            </h1>
            <?php if ($action === 'list'): ?>
                <a href="/admin/faq?action=new" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Ekle
                </a>
            <?php else: ?>
                <a href="/admin/faq" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Geri Dön</a>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cevap</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($faq as $item): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        <?php echo $item['category']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $item['question']; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 line-clamp-2"><?php echo $item['answer']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3 items-center">
                                        <div class="flex flex-col gap-1 mr-2">
                                            <a href="/admin/faq?action=move_up&id=<?php echo $item['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Yukarı Taşı">
                                                <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                            </a>
                                            <a href="/admin/faq?action=move_down&id=<?php echo $item['id']; ?>" class="text-gray-400 hover:text-primary-600" title="Aşağı Taşı">
                                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                        <a href="/admin/faq?action=edit&id=<?php echo $item['id']; ?>" class="text-primary-600 hover:text-primary-900 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <a href="/admin/faq?action=delete&id=<?php echo $item['id']; ?>" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Bu soruyu silmek istediğinize emin misiniz?');">
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
                 <form method="POST" class="space-y-6">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <?php 
                            $categories = ['Genel', 'Hizmetler', 'Teknik', 'Kurumsal', 'Diğer'];
                            $currentCat = isset($editData['category']) ? $editData['category'] : 'Genel';
                            foreach ($categories as $cat) {
                                echo '<option value="'.$cat.'" '.($currentCat === $cat ? 'selected' : '').'>'.$cat.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Soru</label>
                        <input type="text" name="question" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editData['question']) ? $editData['question'] : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cevap</label>
                        <textarea name="answer" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo isset($editData['answer']) ? $editData['answer'] : ''; ?></textarea>
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
