<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$slides = load_json('hero');

// Handle Delete
if (isset($_GET['delete'])) {
    $idxToDelete = (int)$_GET['delete'];
    if (isset($slides[$idxToDelete])) {
        array_splice($slides, $idxToDelete, 1);
        save_json('hero', $slides);
    }
    header('Location: /admin/hero?msg=deleted');
    exit;
}

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idx = isset($_POST['idx']) && $_POST['idx'] !== '' ? (int)$_POST['idx'] : -1;
    $isEdit = $idx >= 0;

    $newSlide = [
        'id' => isset($_POST['id']) ? $_POST['id'] : uniqid(),
        'title' => isset($_POST['title']) ? $_POST['title'] : '',
        'subtitle' => isset($_POST['subtitle']) ? $_POST['subtitle'] : '',
        'description' => isset($_POST['description']) ? $_POST['description'] : '',
        'image' => isset($_POST['existing_image']) ? $_POST['existing_image'] : '',
        'cta' => [
            'text' => isset($_POST['cta_text']) ? $_POST['cta_text'] : '',
            'href' => isset($_POST['cta_href']) ? $_POST['cta_href'] : ''
        ],
        'secondaryCta' => [
            'text' => isset($_POST['sec_cta_text']) ? $_POST['sec_cta_text'] : '',
            'href' => isset($_POST['sec_cta_href']) ? $_POST['sec_cta_href'] : ''
        ]
    ];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploaded = upload_file($_FILES['image']);
        if ($uploaded) {
            $newSlide['image'] = '/images/uploads/' . basename($uploaded);
        }
    }

    if ($isEdit && isset($slides[$idx])) {
        $slides[$idx] = $newSlide;
    } else {
        $slides[] = $newSlide;
    }

    save_json('hero', $slides);
    header('Location: /admin/hero?msg=saved');
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$editSlide = null;
$editIdx = -1;

if ($action === 'edit' && isset($_GET['idx'])) {
    $idx = (int)$_GET['idx'];
    if (isset($slides[$idx])) {
        $editSlide = $slides[$idx];
        $editIdx = $idx;
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Slider (Hero) Yönetimi</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=new" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Yeni Slayt
                </a>
            <?php else: ?>
                <a href="/admin/hero" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Listeye Dön
                </a>
            <?php endif; ?>
        </div>

        <?php if ($action === 'list'): ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($slides as $i => $slide): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start gap-6">
                    <div class="w-48 h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <?php if (isset($slide['image']) && $slide['image']): ?>
                            <img src="<?php echo $slide['image']; ?>" class="w-full h-full object-cover" alt="">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i data-lucide="image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-lg text-gray-900"><?php echo isset($slide['title']) ? $slide['title'] : ''; ?></h3>
                            <div class="flex space-x-2">
                                <a href="?action=edit&idx=<?php echo $i; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                <a href="?delete=<?php echo $i; ?>" onclick="return confirm('Silmek istediğinize emin misiniz?')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                        <p class="text-primary-600 font-medium text-sm mt-1"><?php echo isset($slide['subtitle']) ? $slide['subtitle'] : ''; ?></p>
                        <p class="text-gray-600 text-sm mt-2"><?php echo isset($slide['description']) ? $slide['description'] : ''; ?></p>
                        
                        <div class="mt-4 flex gap-4 text-sm">
                            <?php if(isset($slide['cta']['text']) && $slide['cta']['text']): ?>
                                <span class="px-3 py-1 bg-gray-100 rounded text-gray-600">
                                    Pri: <?php echo $slide['cta']['text']; ?> (<?php echo $slide['cta']['href']; ?>)
                                </span>
                            <?php endif; ?>
                            <?php if(isset($slide['secondaryCta']['text']) && $slide['secondaryCta']['text']): ?>
                                <span class="px-3 py-1 bg-gray-100 rounded text-gray-600">
                                    Sec: <?php echo $slide['secondaryCta']['text']; ?> (<?php echo $slide['secondaryCta']['href']; ?>)
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php else: // Add/Edit Form ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="idx" value="<?php echo $editIdx; ?>">
                    <?php if ($editSlide): ?>
                        <input type="hidden" name="id" value="<?php echo $editSlide['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo isset($editSlide['image']) ? $editSlide['image'] : ''; ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                            <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editSlide['title']) ? $editSlide['title'] : ''; ?>">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık</label>
                            <input type="text" name="subtitle" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editSlide['subtitle']) ? $editSlide['subtitle'] : ''; ?>">
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo isset($editSlide['description']) ? $editSlide['description'] : ''; ?></textarea>
                        </div>
                        
                        <!-- CTA 1 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buton 1 Metin</label>
                            <input type="text" name="cta_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editSlide['cta']['text']) ? $editSlide['cta']['text'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buton 1 Link</label>
                            <input type="text" name="cta_href" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editSlide['cta']['href']) ? $editSlide['cta']['href'] : ''; ?>">
                        </div>

                        <!-- CTA 2 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buton 2 Metin</label>
                            <input type="text" name="sec_cta_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editSlide['secondaryCta']['text']) ? $editSlide['secondaryCta']['text'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buton 2 Link</label>
                            <input type="text" name="sec_cta_href" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo isset($editSlide['secondaryCta']['href']) ? $editSlide['secondaryCta']['href'] : ''; ?>">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Görsel</label>
                        <?php if (isset($editSlide['image']) && $editSlide['image']): ?>
                            <div class="mb-2">
                                <img src="<?php echo $editSlide['image']; ?>" alt="Preview" class="h-32 rounded-lg object-cover">
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
