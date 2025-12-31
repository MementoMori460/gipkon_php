<?php
// includes/header.php
// Assumes functions.php is already included and $settings, $menu variables are available
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_value($settings, 'siteName', 'GIPKON'); ?></title>
    <?php if (isset($settings['branding']['favicon']) && !empty($settings['branding']['favicon'])): ?>
        <link rel="icon" href="<?php echo url($settings['branding']['favicon']); ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo url($settings['branding']['favicon']); ?>" type="image/x-icon">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo url('assets/css/main.css'); ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Mobile menu transitions */
        .mobile-menu-enter {
            animation: slideDown 0.3s ease-out forwards;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        :root {
            <?php 
            $t = isset($settings['theme']) ? $settings['theme'] : [];
            $primary = isset($t['primaryColor']) ? $t['primaryColor'] : '#1969d2';
            $secondary = isset($t['secondaryColor']) ? $t['secondaryColor'] : '#94a3b8';
            
            // Generate Shades
            echo '--primary-600: ' . $primary . ';'; 
            echo '--primary-700: ' . adjustBrightness($primary, -0.1) . ';'; // Darker
            echo '--primary-500: ' . adjustBrightness($primary, 0.1) . ';'; // Lighter
            // Also map to generic primary for some tailwind classes if needed
            echo '--primary-color: ' . $primary . ';';
            
            echo '--secondary-600: ' . $secondary . ';';

            echo '--page-bg: ' . (isset($t['backgroundColor']) ? $t['backgroundColor'] : '#ffffff') . ';';
            echo '--page-text: ' . (isset($t['textColor']) ? $t['textColor'] : '#334155') . ';';

            echo '--header-bg: ' . (isset($t['header']['background']) ? $t['header']['background'] : '#ffffff') . ';';
            echo '--header-text: ' . (isset($t['header']['textColor']) ? $t['header']['textColor'] : '#334155') . ';';
            echo '--header-nav-text: ' . (isset($t['header']['navTextColor']) ? $t['header']['navTextColor'] : '#334155') . ';';
            echo '--header-contact-text: ' . (isset($t['header']['contactTextColor']) ? $t['header']['contactTextColor'] : '#ffffff') . ';';

            echo '--footer-bg: ' . (isset($t['footer']['background']) ? $t['footer']['background'] : '#f8fafc') . ';';
            echo '--footer-text: ' . (isset($t['footer']['textColor']) ? $t['footer']['textColor'] : '#64748b') . ';';

            echo '--card-bg: ' . (isset($t['card']['background']) ? $t['card']['background'] : '#ffffff') . ';';
            echo '--card-text: ' . (isset($t['card']['textColor']) ? $t['card']['textColor'] : '#334155') . ';';
            echo '--card-border: ' . (isset($t['card']['borderColor']) ? $t['card']['borderColor'] : '#e2e8f0') . ';';

            echo '--btn-primary-bg: ' . (isset($t['components']['buttonPrimaryBg']) ? $t['components']['buttonPrimaryBg'] : '#475569') . ';';
            echo '--btn-primary-text: ' . (isset($t['components']['buttonPrimaryText']) ? $t['components']['buttonPrimaryText'] : '#ffffff') . ';';
            echo '--btn-secondary-bg: ' . (isset($t['components']['buttonSecondaryBg']) ? $t['components']['buttonSecondaryBg'] : '#f1f5f9') . ';';
            echo '--btn-secondary-text: ' . (isset($t['components']['buttonSecondaryText']) ? $t['components']['buttonSecondaryText'] : '#475569') . ';';
            ?>
        }
    </style>
</head>
<body class="bg-[var(--page-bg)] text-[var(--page-text)] font-sans antialiased">
    
    <!-- Header -->
    <header class="sticky top-0 z-50 backdrop-blur-sm border-b border-gray-200" style="background-color: var(--header-bg); color: var(--header-text);">
        <!-- Top Bar -->
        <div class="py-2" style="background-color: var(--primary-700); color: var(--header-contact-text);">
            <div class="container mx-auto px-4 flex justify-between items-center text-sm">
                <div class="flex gap-6">
                    <a href="tel:<?php echo $settings['contact']['phone']; ?>" class="flex items-center gap-2 hover:text-primary-200 transition-colors">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span><?php echo $settings['contact']['phone']; ?></span>
                    </a>
                    <a href="mailto:<?php echo $settings['contact']['email']; ?>" class="flex items-center gap-2 hover:text-primary-200 transition-colors">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span><?php echo $settings['contact']['email']; ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <a href="<?php echo url('/'); ?>" class="flex items-center">
                    <?php if (!empty($settings['branding']['logo'])): ?>
                        <div class="relative h-16 md:h-28 w-auto">
                            <img 
                                src="<?php echo url($settings['branding']['logo']); ?>" 
                                alt="<?php echo $settings['siteName']; ?>" 
                                class="object-contain h-full w-full"
                            >
                        </div>
                    <?php else: ?>
                        <span class="text-xl md:text-2xl font-display font-bold text-primary-700">
                            GIPKON
                        </span>
                        <span class="ml-2 text-xs md:text-sm text-secondary-600">TEKNOLOJİ</span>
                    <?php endif; ?>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-1">
                    <?php 
                    $navItems = isset($menu['header']) ? $menu['header'] : [];
                    
                    foreach ($navItems as $item): 
                        if (!$item['active']) continue;

                        $subItems = isset($item['items']) ? $item['items'] : null;
                        
                        // Using hardcoded services for now if dynamic fetching isn't fully set in menu.json
                        if ($item['name'] === 'HİZMETLERİMİZ' && !empty($services)) {
                            $subItems = [];
                            $subItems[] = ['name' => 'Hizmetlere Genel Bakış', 'href' => '/hizmetler', 'active' => true];
                            foreach ($services as $service) {
                                if (isset($service['isActive']) && $service['isActive'] === false) continue;
                                $subItems[] = [
                                    'name' => $service['title'], 
                                    'href' => '/hizmetler/' . $service['slug'], 
                                    'active' => true
                                ];
                            }
                        }
                    ?>
                        <div class="relative py-4 group nav-dropdown-group">
                             <?php if ($subItems): ?>
                                <button class="px-4 py-2 text-sm font-medium hover:text-primary-600 transition-colors flex items-center gap-1 cursor-default" style="color: var(--header-nav-text);">
                                    <?php echo $item['name']; ?>
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>
                                <!-- Dropdown -->
                                <div class="dropdown-menu absolute top-full left-0 mt-1 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 hidden z-50">
                                    <?php foreach ($subItems as $subItem): 
                                        if (isset($subItem['active']) && !$subItem['active']) continue;
                                    ?>
                                        <a href="<?php echo url($subItem['href']); ?>" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                            <?php echo $subItem['name']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                             <?php else: ?>
                                <a href="<?php echo url($item['href']); ?>" class="px-4 py-2 text-sm font-medium hover:opacity-80 transition-colors" style="color: var(--header-nav-text);">
                                    <?php echo $item['name']; ?>
                                </a>
                             <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center gap-4">
                     <button id="mobile-menu-btn" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="menu" class="w-6 h-6 text-secondary-700"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 bg-white absolute top-20 left-0 w-full shadow-lg z-40">
             <div class="container mx-auto px-4 py-4 space-y-2">
                <?php foreach ($navItems as $item): 
                    if (!$item['active']) continue;
                    $subItems = isset($item['items']) ? $item['items'] : null;
                     if ($item['name'] === 'HİZMETLERİMİZ' && !empty($services)) {
                        $subItems = [];
                        $subItems[] = ['name' => 'Hizmetlere Genel Bakış', 'href' => '/hizmetler', 'active' => true];
                        foreach ($services as $service) {
                            if (isset($service['isActive']) && $service['isActive'] === false) continue;
                            $subItems[] = ['name' => $service['title'], 'href' => '/hizmetler/' . $service['slug'], 'active' => true];
                        }
                    }
                ?>
                    <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                         <?php if ($subItems): ?>
                            <button class="mobile-dropdown-btn w-full text-left px-4 py-2 text-sm font-medium text-secondary-700 hover:bg-primary-50 rounded-lg transition-colors flex items-center justify-between">
                                <?php echo $item['name']; ?>
                                <i data-lucide="chevron-down" class="chevron-icon w-4 h-4 transition-transform"></i>
                            </button>
                            <div class="hidden ml-4 mt-1 space-y-1 bg-gray-50 rounded-lg p-2">
                                <?php foreach ($subItems as $subItem): 
                                     if (isset($subItem['active']) && !$subItem['active']) continue;
                                ?>
                                    <a href="<?php echo url($subItem['href']); ?>" class="block px-4 py-2 text-sm text-secondary-600 hover:bg-white hover:text-primary-600 rounded-lg transition-colors">
                                        <?php echo $subItem['name']; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                         <?php else: ?>
                            <a href="<?php echo url($item['href']); ?>" class="block px-4 py-2 text-sm font-medium text-secondary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <?php echo $item['name']; ?>
                            </a>
                         <?php endif; ?>
                    </div>
                <?php endforeach; ?>
             </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop Dropdown Delay Logic
            const dropdownGroups = document.querySelectorAll('.nav-dropdown-group');

            dropdownGroups.forEach(group => {
                const dropdown = group.querySelector('.dropdown-menu');
                let timeoutId;

                if (!dropdown) return;

                group.addEventListener('mouseenter', () => {
                    if(timeoutId) clearTimeout(timeoutId);
                    dropdown.classList.remove('hidden');
                });

                group.addEventListener('mouseleave', () => {
                    timeoutId = setTimeout(() => {
                        dropdown.classList.add('hidden');
                    }, 300); // 300ms delay before closing
                });
            });

            // Mobile Menu Toggle logic (kept inline for simplicity here, or can be in main.js)
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                   mobileMenu.classList.toggle('hidden');
                   // Toggle icon if needed
                });
            }

             // Mobile Dropdown Toggles
            const mobileDropdownBtns = document.querySelectorAll('.mobile-dropdown-btn');
            mobileDropdownBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.chevron-icon');
                    // Find actual svg if lucide replaced it
                    const svgIcon = this.querySelector('svg.chevron-icon') || icon; 
                    
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        if (svgIcon) svgIcon.style.transform = 'rotate(180deg)';
                    } else {
                        content.classList.add('hidden');
                        if (svgIcon) svgIcon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });
    </script>
