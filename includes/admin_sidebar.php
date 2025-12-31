<?php
// includes/admin_sidebar.php
$currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-screen sticky top-0">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-primary-700 flex items-center">
            <i data-lucide="layout-dashboard" class="w-6 h-6 mr-2"></i>
            Admin Panel
        </h2>
    </div>
    <nav class="flex-1 overflow-y-auto py-4">
        <?php
        $menuItems = [
            ['url' => '/admin', 'label' => 'Genel Bakış', 'icon' => 'home'],
            ['url' => '/admin/theme', 'label' => 'Tema & Tasarım', 'icon' => 'palette'],
            ['url' => '/admin/hero', 'label' => 'Hero / Slider', 'icon' => 'monitor-play'],
            ['url' => '/admin/menu', 'label' => 'Menü Yönetimi', 'icon' => 'menu'],
            ['url' => '/admin/services', 'label' => 'Hizmetler', 'icon' => 'box'],
            ['url' => '/admin/sectors', 'label' => 'Sektörler', 'icon' => 'layers'],
            ['url' => '/admin/projects', 'label' => 'Projeler', 'icon' => 'briefcase'],
            ['url' => '/admin/references', 'label' => 'Referanslar', 'icon' => 'users'],
            ['url' => '/admin/media', 'label' => 'Medya Kütüphanesi', 'icon' => 'image'],
            ['url' => '/admin/catalogs', 'label' => 'Kataloglar', 'icon' => 'book'],
            ['url' => '/admin/faq', 'label' => 'S.S.S.', 'icon' => 'help-circle'],
            ['url' => '/admin/video', 'label' => 'Video', 'icon' => 'video'],
            ['url' => '/admin/backup', 'label' => 'Yedekleme & Sistem', 'icon' => 'save'],
            ['url' => '/admin/update', 'label' => 'Sistem Güncelleme', 'icon' => 'refresh-cw'],
            ['url' => '/admin/settings', 'label' => 'Ayarlar', 'icon' => 'settings'],
        ];

        foreach ($menuItems as $item) {
            $fullUrl = url($item['url']);
            // Check for exact match or prefix match (but ensure we don't match /admin/settings against /admin/set...)
            // Simplest way: check if currentPage starts with fullUrl
            $isActive = ($currentPage === $fullUrl || ($item['url'] !== '/admin' && strpos($currentPage, $fullUrl) === 0));
            $bgClass = $isActive ? 'bg-primary-50 text-primary-700 font-medium' : 'text-secondary-600 hover:bg-gray-50 hover:text-primary-600';
            
            echo '<a href="' . $fullUrl . '" class="flex items-center px-6 py-3 transition-colors ' . $bgClass . '">';
            echo '<i data-lucide="' . $item['icon'] . '" class="w-5 h-5 mr-3"></i>';
            echo $item['label'];
            echo '</a>';
        }
        ?>
    </nav>
    <div class="p-4 border-t border-gray-100">
        <a href="<?php echo url('/admin/logout'); ?>" class="flex items-center px-6 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
            Çıkış Yap
        </a>
    </div>
</aside>
