<?php
// pages/admin/theme.php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$success = '';
$settingsData = load_json('settings');

// Helper
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
    $theme = [];
    $theme['primaryColor'] = $_POST['primaryColor'];
    $theme['secondaryColor'] = $_POST['secondaryColor'];
    $theme['backgroundColor'] = $_POST['backgroundColor'];
    $theme['textColor'] = $_POST['textColor'];

    $theme['header'] = [
        'background' => $_POST['header_bg'],
        'textColor' => $_POST['header_text'],
        'navTextColor' => $_POST['header_nav_text'],
        'contactTextColor' => $_POST['header_contact_text']
    ];

    $theme['footer'] = [
        'background' => $_POST['footer_bg'],
        'textColor' => $_POST['footer_text']
    ];

    $theme['card'] = [
        'background' => $_POST['card_bg'],
        'textColor' => $_POST['card_text'],
        'borderColor' => $_POST['card_border']
    ];

    $theme['components'] = [
        'buttonPrimaryBg' => $_POST['btn_primary_bg'],
        'buttonPrimaryText' => $_POST['btn_primary_text'],
        'buttonSecondaryBg' => $_POST['btn_secondary_bg'],
        'buttonSecondaryText' => $_POST['btn_secondary_text'],
    ];

    // Merge only theme part to avoid data loss in other setting areas
    $settingsData['theme'] = $theme;

    if (save_json('settings', $settingsData)) {
        $success = 'Tema ayarları güncellendi.';
        $settings = $settingsData;
    }
}
$t = isset($settingsData['theme']) ? $settingsData['theme'] : [];

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <div class="flex justify-between items-center mb-8 bg-gray-50/95 sticky top-0 z-10 py-4 border-b border-gray-200 backdrop-blur-sm px-1">
            <h1 class="text-2xl font-bold text-gray-800">Tema & Tasarım Yönetimi</h1>
            <div class="flex items-center gap-4">
                <button type="button" onclick="resetToDefaults()" class="flex items-center gap-2 bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    Varsayılanlara Dön
                </button>
                <button onclick="document.getElementById('themeForm').submit()" class="flex items-center gap-2 bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Kaydet
                </button>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="themeForm" class="grid grid-cols-1 gap-8 max-w-5xl">
            
            <!-- Global Colors -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-6 pb-2 border-b flex items-center">
                    <i data-lucide="palette" class="w-5 h-5 mr-2 text-primary-600"></i> Genel Renkler
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ana Renk (Primary)</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="primaryColor" value="<?php echo get_set_val($t, ['primaryColor'], '#1969d2'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="primaryColor">
                            <input type="text" name="primaryColor" value="<?php echo get_set_val($t, ['primaryColor'], '#1969d2'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('primaryColor').value = this.value">
                            <button type="button" onclick="setVal('primaryColor', '#1969d2')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sitenin ana rengidir. Butonlar, linkler, başlık vurguları ve ikonlarda kullanılır.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İkincil Renk</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="secondaryColor" value="<?php echo get_set_val($t, ['secondaryColor'], '#94a3b8'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="secondaryColor">
                            <input type="text" name="secondaryColor" value="<?php echo get_set_val($t, ['secondaryColor'], '#94a3b8'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('secondaryColor').value = this.value">
                            <button type="button" onclick="setVal('secondaryColor', '#94a3b8')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Yan öğeler, alt başlıklar ve daha az vurgulu metinlerde kullanılır.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sayfa Arkaplanı</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="backgroundColor" value="<?php echo get_set_val($t, ['backgroundColor'], '#ffffff'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="backgroundColor">
                            <input type="text" name="backgroundColor" value="<?php echo get_set_val($t, ['backgroundColor'], '#ffffff'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('backgroundColor').value = this.value">
                            <button type="button" onclick="setVal('backgroundColor', '#ffffff')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sitenin genel gövde (body) arka plan rengidir.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sayfa Metin Rengi</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="textColor" value="<?php echo get_set_val($t, ['textColor'], '#334155'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="textColor">
                            <input type="text" name="textColor" value="<?php echo get_set_val($t, ['textColor'], '#334155'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('textColor').value = this.value">
                            <button type="button" onclick="setVal('textColor', '#334155')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sitedeki düz paragrafların ve içeriğin genel yazı rengidir.</p>
                    </div>
                </div>
            </div>

            <!-- Header Colors -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-6 pb-2 border-b flex items-center">
                    <i data-lucide="layout-template" class="w-5 h-5 mr-2 text-primary-600"></i> Üst Menü (Header)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Header Arkaplan</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="header_bg" value="<?php echo get_set_val($t, ['header', 'background'], '#ffffff'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="header_bg">
                            <input type="text" name="header_bg" value="<?php echo get_set_val($t, ['header', 'background'], '#ffffff'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('header_bg').value = this.value">
                            <button type="button" onclick="setVal('header_bg', '#ffffff')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Menü Link Rengi</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="header_nav_text" value="<?php echo get_set_val($t, ['header', 'navTextColor'], '#334155'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="header_nav_text">
                            <input type="text" name="header_nav_text" value="<?php echo get_set_val($t, ['header', 'navTextColor'], '#334155'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('header_nav_text').value = this.value">
                            <button type="button" onclick="setVal('header_nav_text', '#334155')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Üst Bant İletişim Rengi</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="header_contact_text" value="<?php echo get_set_val($t, ['header', 'contactTextColor'], '#ffffff'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="header_contact_text">
                            <input type="text" name="header_contact_text" value="<?php echo get_set_val($t, ['header', 'contactTextColor'], '#ffffff'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('header_contact_text').value = this.value">
                            <button type="button" onclick="setVal('header_contact_text', '#ffffff')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                         <p class="text-xs text-gray-400 mt-1">En üstteki koyu bant içindeki telefon/mail rengi.</p>
                    </div>
                    <div>
                         <label class="block text-sm font-medium text-gray-700 mb-2">Logo Yanı Metin</label>
                         <div class="flex items-center gap-2">
                            <input type="color" name="header_text" value="<?php echo get_set_val($t, ['header', 'textColor'], '#334155'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="header_text">
                            <input type="text" name="header_text" value="<?php echo get_set_val($t, ['header', 'textColor'], '#334155'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('header_text').value = this.value">
                            <button type="button" onclick="setVal('header_text', '#334155')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Colors -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-6 pb-2 border-b flex items-center">
                    <i data-lucide="layout-bottom" class="w-5 h-5 mr-2 text-primary-600"></i> Alt Menü (Footer)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Footer Arkaplan</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="footer_bg" value="<?php echo get_set_val($t, ['footer', 'background'], '#f8fafc'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="footer_bg">
                            <input type="text" name="footer_bg" value="<?php echo get_set_val($t, ['footer', 'background'], '#f8fafc'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('footer_bg').value = this.value">
                            <button type="button" onclick="setVal('footer_bg', '#f8fafc')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Footer Metin Rengi</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="footer_text" value="<?php echo get_set_val($t, ['footer', 'textColor'], '#64748b'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="footer_text">
                            <input type="text" name="footer_text" value="<?php echo get_set_val($t, ['footer', 'textColor'], '#64748b'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('footer_text').value = this.value">
                            <button type="button" onclick="setVal('footer_text', '#64748b')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Components Colors -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-semibold mb-6 pb-2 border-b flex items-center">
                    <i data-lucide="box-select" class="w-5 h-5 mr-2 text-primary-600"></i> Bileşenler (Butonlar ve Kartlar)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kart Arkaplan</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="card_bg" value="<?php echo get_set_val($t, ['card', 'background'], '#ffffff'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="card_bg">
                            <input type="text" name="card_bg" value="<?php echo get_set_val($t, ['card', 'background'], '#ffffff'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('card_bg').value = this.value">
                            <button type="button" onclick="setVal('card_bg', '#ffffff')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kart Metin</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="card_text" value="<?php echo get_set_val($t, ['card', 'textColor'], '#334155'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="card_text">
                            <input type="text" name="card_text" value="<?php echo get_set_val($t, ['card', 'textColor'], '#334155'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('card_text').value = this.value">
                            <button type="button" onclick="setVal('card_text', '#334155')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kart Çerçeve (Border)</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="card_border" value="<?php echo get_set_val($t, ['card', 'borderColor'], '#e2e8f0'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="card_border">
                            <input type="text" name="card_border" value="<?php echo get_set_val($t, ['card', 'borderColor'], '#e2e8f0'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('card_border').value = this.value">
                            <button type="button" onclick="setVal('card_border', '#e2e8f0')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                     <div><!-- Spacer --></div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Buton Arkaplan</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="btn_primary_bg" value="<?php echo get_set_val($t, ['components', 'buttonPrimaryBg'], '#475569'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="btn_primary_bg">
                            <input type="text" name="btn_primary_bg" value="<?php echo get_set_val($t, ['components', 'buttonPrimaryBg'], '#475569'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('btn_primary_bg').value = this.value">
                            <button type="button" onclick="setVal('btn_primary_bg', '#475569')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Buton Metin</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="btn_primary_text" value="<?php echo get_set_val($t, ['components', 'buttonPrimaryText'], '#ffffff'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="btn_primary_text">
                            <input type="text" name="btn_primary_text" value="<?php echo get_set_val($t, ['components', 'buttonPrimaryText'], '#ffffff'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('btn_primary_text').value = this.value">
                            <button type="button" onclick="setVal('btn_primary_text', '#ffffff')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Buton Arkaplan</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="btn_secondary_bg" value="<?php echo get_set_val($t, ['components', 'buttonSecondaryBg'], '#f1f5f9'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="btn_secondary_bg">
                            <input type="text" name="btn_secondary_bg" value="<?php echo get_set_val($t, ['components', 'buttonSecondaryBg'], '#f1f5f9'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('btn_secondary_bg').value = this.value">
                            <button type="button" onclick="setVal('btn_secondary_bg', '#f1f5f9')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Buton Metin</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="btn_secondary_text" value="<?php echo get_set_val($t, ['components', 'buttonSecondaryText'], '#475569'); ?>" class="h-10 w-12 rounded border border-gray-300 p-1 cursor-pointer" id="btn_secondary_text">
                            <input type="text" name="btn_secondary_text" value="<?php echo get_set_val($t, ['components', 'buttonSecondaryText'], '#475569'); ?>" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase" oninput="document.getElementById('btn_secondary_text').value = this.value">
                            <button type="button" onclick="setVal('btn_secondary_text', '#475569')" class="p-2 text-gray-400 hover:text-primary-600 transition-colors" title="Varsayılana Dön">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </main>
</div>

<script>
    lucide.createIcons();

    function setVal(name, val) {
        // Update all inputs with this name (both color picker and text input)
        const inputs = document.querySelectorAll(`input[name="${name}"]`);
        inputs.forEach(input => {
            input.value = val;
        });
    }

    function resetToDefaults() {
        if(!confirm('Tüm renk ayarlarını varsayılan GIPKON temasına sıfırlamak istediğinize emin misiniz?')) return;

        setVal('primaryColor', '#1969d2');
        setVal('secondaryColor', '#94a3b8');
        setVal('backgroundColor', '#ffffff');
        setVal('textColor', '#334155');

        setVal('header_bg', '#ffffff');
        setVal('header_text', '#334155');
        setVal('header_nav_text', '#334155');
        setVal('header_contact_text', '#ffffff');

        setVal('footer_bg', '#f8fafc');
        setVal('footer_text', '#64748b');

        setVal('card_bg', '#ffffff');
        setVal('card_text', '#334155');
        setVal('card_border', '#e2e8f0');

        setVal('btn_primary_bg', '#475569');
        setVal('btn_primary_text', '#ffffff');
        setVal('btn_secondary_bg', '#f1f5f9');
        setVal('btn_secondary_text', '#475569');
    }

    // Sync Hex Text Inputs with Color Pickers bidirectional
    document.querySelectorAll('input[type="color"]').forEach(picker => {
        let textInput = picker.nextElementSibling;
        
        picker.addEventListener('input', (e) => {
            textInput.value = e.target.value;
        });

        textInput.addEventListener('input', (e) => {
            if(e.target.value.length === 7 && e.target.value.startsWith('#')) {
                picker.value = e.target.value;
            }
        });
    });
</script>
</body>
</html>
