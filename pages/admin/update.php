<?php
// pages/admin/update.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$output = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Check for git directory
    if (!is_dir(BASE_PATH . '/.git')) {
        $status = 'error';
        $output = "Hata: .git klasörü bulunamadı. Bu özellik sadece Git ile yönetilen projelerde çalışır.";
    } else {
        // Execute git pull
        // Note: This requires the PHP user to have permissions to run git and write to files
        // and SSH keys or credentials to be set up if the repo is private.
        $command = 'cd ' . BASE_PATH . ' && git pull origin main 2>&1';
        exec($command, $outputLines, $returnVar);
        
        $output = implode("\n", $outputLines);
        
        if ($returnVar === 0) {
            $status = 'success';
            $successMsg = "Sistem başarıyla güncellendi.";
        } else {
            $status = 'error';
            $errorMsg = "Güncelleme sırasında bir hata oluştu.";
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
            <h1 class="text-2xl font-bold text-gray-800">Sistem Güncelleme</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
            <div class="mb-6">
                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50" role="alert">
                    <i data-lucide="info" class="w-5 h-5 mr-3"></i>
                    <div>
                        <span class="font-medium">Dikkat:</span> Bu işlem Github deposundaki son değişiklikleri (main branch) sunucuya çeker ve mevcut dosyaların üzerine yazar.
                    </div>
                </div>
                
                <p class="text-gray-600 mb-4">
                    Projenizin en güncel versiyonunu Github üzerinden çekmek için aşağıdaki butonu kullanabilirsiniz.
                    Bu işlem sırasında internet bağlantısı ve Git yapılandırmasının sunucuda kurulu olması gerekmektedir.
                </p>
            </div>

            <?php if ($status === 'success'): ?>
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Başarılı!</span> <?php echo $successMsg; ?>
                </div>
            <?php elseif ($status === 'error'): ?>
                 <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <span class="font-medium">Hata!</span> <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>

            <?php if ($output): ?>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İşlem Çıktısı (Log):</label>
                    <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto font-mono"><?php echo htmlspecialchars($output); ?></pre>
                </div>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" name="update" class="w-full flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors" onclick="return confirm('Sistemi güncellemek istediğinize emin misiniz? Bu işlem geri alınamaz.');">
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i>
                    Sistemi Güncelle (Git Pull)
                </button>
            </form>
        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
