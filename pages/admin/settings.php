<?php
// pages/admin/settings.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$settingsData = load_json('settings');

// Helper to safely get nested value
function get_set_val($arr, $keys, $default = '') {
    $current = $arr;
    foreach($keys as $key) {
        if(isset($current[$key])) {
            $current = $current[$key];
        } else {
            return $default;
        }
    }
    return is_array($current) ? $default : $current;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic Settings
    $settingsData['siteName'] = isset($_POST['siteName']) ? $_POST['siteName'] : '';
    $settingsData['siteDescription'] = isset($_POST['siteDescription']) ? $_POST['siteDescription'] : '';
    
    // Contact Info
    $settingsData['contact']['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
    $settingsData['contact']['gsm'] = isset($_POST['gsm']) ? $_POST['gsm'] : '';
    $settingsData['contact']['email'] = isset($_POST['email']) ? $_POST['email'] : '';
    $settingsData['contact']['address'] = isset($_POST['address']) ? $_POST['address'] : '';
    $settingsData['contact']['mapUrl'] = isset($_POST['mapUrl']) ? $_POST['mapUrl'] : '';

    // Social Media
    $settingsData['socialMedia'] = [
        'facebook' => isset($_POST['facebook']) ? $_POST['facebook'] : '',
        'twitter' => isset($_POST['twitter']) ? $_POST['twitter'] : '',
        'linkedin' => isset($_POST['linkedin']) ? $_POST['linkedin'] : '',
        'instagram' => isset($_POST['instagram']) ? $_POST['instagram'] : ''
    ];

    // Office Hours
    $settingsData['officeHours'] = [
        'weekdays' => isset($_POST['oh_weekdays']) ? $_POST['oh_weekdays'] : '',
        'saturday' => isset($_POST['oh_saturday']) ? $_POST['oh_saturday'] : '',
        'sunday' => isset($_POST['oh_sunday']) ? $_POST['oh_sunday'] : ''
    ];

    // SMTP Settings
    if (!isset($settingsData['smtp'])) $settingsData['smtp'] = [];
    $settingsData['smtp']['host'] = isset($_POST['smtp_host']) ? $_POST['smtp_host'] : '';
    $settingsData['smtp']['port'] = isset($_POST['smtp_port']) ? $_POST['smtp_port'] : '587';
    $settingsData['smtp']['username'] = isset($_POST['smtp_username']) ? $_POST['smtp_username'] : '';
    $settingsData['smtp']['password'] = isset($_POST['smtp_password']) ? $_POST['smtp_password'] : '';
    $settingsData['smtp']['secure'] = isset($_POST['smtp_secure']) ? $_POST['smtp_secure'] : 'tls';
    $settingsData['smtp']['fromName'] = isset($_POST['smtp_fromName']) ? $_POST['smtp_fromName'] : '';
    $settingsData['smtp']['fromEmail'] = isset($_POST['smtp_fromEmail']) ? $_POST['smtp_fromEmail'] : '';
    
    // Logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $path = upload_file($_FILES['logo'], 'branding');
        if($path) $settingsData['branding']['logo'] = $path;
    }

    // Favicon upload
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
        $path = upload_file($_FILES['favicon'], 'branding');
        if($path) $settingsData['branding']['favicon'] = $path;
    }

    if (save_json('settings', $settingsData)) {
        $success = 'Ayarlar güncellendi.';
        $settings = $settingsData; // Update global variable for header rendering
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-8">Site Ayarları</h1>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-4xl">
            <form method="POST" enctype="multipart/form-data" class="space-y-8">
                
                <!-- Site Identity -->
                <div class="border-b border-gray-100 pb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="globe" class="w-5 h-5 mr-2 text-primary-600"></i> Site Kimliği
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Adı</label>
                            <input type="text" name="siteName" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['siteName']); ?>">
                        </div>
                        <div class="md:col-span-2">
                             <label class="block text-sm font-medium text-gray-700 mb-2">Site Açıklaması (Meta Description)</label>
                             <input type="text" name="siteDescription" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['siteDescription']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                            <?php if (!empty($settingsData['branding']['logo'])): ?>
                                <img src="<?php echo $settingsData['branding']['logo']; ?>" class="h-12 mb-3 border border-gray-200 p-1 rounded bg-gray-50 object-contain">
                            <?php endif; ?>
                            <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            <?php if (!empty($settingsData['branding']['favicon'])): ?>
                                <img src="<?php echo $settingsData['branding']['favicon']; ?>" class="h-8 w-8 mb-3 border border-gray-200 p-1 rounded bg-gray-50 object-contain">
                            <?php endif; ?>
                            <input type="file" name="favicon" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="border-b border-gray-100 pb-8">
                     <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="phone" class="w-5 h-5 mr-2 text-primary-600"></i> İletişim Bilgileri
                     </h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                            <input type="text" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['contact', 'phone']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Acil İletişim / GSM</label>
                            <input type="text" name="gsm" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['contact', 'gsm']); ?>">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                            <input type="text" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['contact', 'email']); ?>">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo get_set_val($settingsData, ['contact', 'address']); ?></textarea>
                        </div>
                         <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps Embed URL</label>
                            <input type="text" name="mapUrl" class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm text-gray-600" value="<?php echo htmlspecialchars(get_set_val($settingsData, ['contact', 'mapUrl'])); ?>">
                            <p class="text-xs text-gray-500 mt-1">Google Maps'ten "Harisayı yerleştir" (embed) kodu değil, sadece src="" içindeki URL'i yapıştırın.</p>
                        </div>
                     </div>
                </div>

                <!-- Office Hours -->
                <div class="border-b border-gray-100 pb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="clock" class="w-5 h-5 mr-2 text-primary-600"></i> Çalışma Saatleri
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hafta İçi</label>
                            <input type="text" name="oh_weekdays" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['officeHours', 'weekdays']); ?>" placeholder="09:00 - 18:00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cumartesi</label>
                            <input type="text" name="oh_saturday" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['officeHours', 'saturday']); ?>" placeholder="09:00 - 14:00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pazar</label>
                            <input type="text" name="oh_sunday" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['officeHours', 'sunday']); ?>" placeholder="Kapalı">
                        </div>
                    </div>
                </div>

                <!-- SMTP Settings -->
                <div class="border-b border-gray-100 pb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="mail" class="w-5 h-5 mr-2 text-primary-600"></i> SMTP Ayarları (E-posta)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Sunucusu</label>
                            <input type="text" name="smtp_host" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['smtp', 'host']); ?>" placeholder="smtp.example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="text" name="smtp_port" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['smtp', 'port'], '587'); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kullanıcı Adı (E-posta)</label>
                            <input type="text" name="smtp_username" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['smtp', 'username']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Şifre</label>
                            <input type="password" name="smtp_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['smtp', 'password']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gönderen Adı</label>
                            <input type="text" name="smtp_fromName" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['smtp', 'fromName']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gönderen E-posta</label>
                            <input type="text" name="smtp_fromEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['smtp', 'fromEmail']); ?>">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Güvenlik</label>
                            <select name="smtp_secure" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="tls" <?php echo (get_set_val($settingsData, ['smtp', 'secure']) === 'tls') ? 'selected' : ''; ?>>TLS</option>
                                <option value="ssl" <?php echo (get_set_val($settingsData, ['smtp', 'secure']) === 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                <option value="none" <?php echo (get_set_val($settingsData, ['smtp', 'secure']) === 'none') ? 'selected' : ''; ?>>Yok</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                 <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="share-2" class="w-5 h-5 mr-2 text-primary-600"></i> Sosyal Medya
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                            <input type="text" name="facebook" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['socialMedia', 'facebook']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Twitter / X URL</label>
                            <input type="text" name="twitter" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['socialMedia', 'twitter']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn URL</label>
                            <input type="text" name="linkedin" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['socialMedia', 'linkedin']); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                            <input type="text" name="instagram" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo get_set_val($settingsData, ['socialMedia', 'instagram']); ?>">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end sticky bottom-0 bg-gray-50/90 py-4 backdrop-blur-sm border-t border-gray-200 mt-8">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-shadow shadow-md">
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
