<?php
// includes/footer.php
?>
    <footer class="border-t border-gray-200 mt-auto" style="background-color: var(--footer-bg); color: var(--footer-text);">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Wrapper for Info -->
                <div>
                     <?php if (!empty($settings['branding']['logo'])): ?>
                        <div class="relative h-12 w-auto aspect-[3/1] mb-6">
                            <img 
                                src="<?php echo url($settings['branding']['logo']); ?>" 
                                alt="<?php echo $settings['siteName']; ?>" 
                                class="object-contain h-full w-left"
                            >
                        </div>
                    <?php else: ?>
                        <div class="text-2xl font-display font-bold text-primary-700 mb-6">GIPKON</div>
                    <?php endif; ?>
                    <p class="text-sm opacity-80 mb-6">
                        <?php echo $settings['siteDescription']; ?>
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-primary-700">Hızlı Bağlantılar</h3>
                     <ul class="space-y-3">
                        <?php 
                        $quickLinks = isset($menu['footer']['quickLinks']) ? $menu['footer']['quickLinks'] : [];
                        foreach ($quickLinks as $link):
                            if (!$link['active']) continue;
                        ?>
                            <li>
                                <a href="<?php echo url($link['href']); ?>" class="text-sm hover:text-primary-600 transition-colors">
                                    <?php echo $link['name']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                     </ul>
                </div>

                <!-- Services -->
                <div>
                     <h3 class="text-lg font-semibold mb-6 text-primary-700">Hizmetlerimiz</h3>
                     <ul class="space-y-3">
                        <?php foreach (array_slice($services, 0, 6) as $service): ?>
                            <li>
                                <a href="<?php echo url('/hizmetler/' . $service['slug']); ?>" class="text-sm hover:text-primary-600 transition-colors">
                                    <?php echo $service['title']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                     </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-primary-700">İletişim</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="text-xl">📍</span>
                            <span class="text-sm leading-relaxed whitespace-pre-line"><?php echo $settings['contact']['address']; ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-xl">📞</span>
                            <a href="tel:<?php echo $settings['contact']['phone']; ?>" class="text-sm hover:text-primary-600 transition-colors">
                                <?php echo $settings['contact']['phone']; ?>
                            </a>
                        </li>
                        <?php if(!empty($settings['contact']['gsm'])): ?>
                        <li class="flex items-center gap-3">
                            <span class="text-xl">📱</span>
                            <a href="tel:<?php echo $settings['contact']['gsm']; ?>" class="text-sm hover:text-primary-600 transition-colors">
                                <?php echo $settings['contact']['gsm']; ?> <span class="text-xs opacity-70">(Acil)</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="flex items-center gap-3">
                            <span class="text-xl">✉️</span>
                            <a href="mailto:<?php echo $settings['contact']['email']; ?>" class="text-sm hover:text-primary-600 transition-colors">
                                <?php echo $settings['contact']['email']; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
             <div class="border-t border-gray-200 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm opacity-60">
                        &copy; <?php echo date('Y'); ?> <?php echo $settings['siteName']; ?>. Tüm hakları saklıdır.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Initialize Lucide Icons globally
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
