<?php
// pages/contact.php
require_once __DIR__ . '/../functions.php';
render_header();
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-3xl md:text-5xl font-display font-bold mb-6">İletişim</h1>
                <p class="text-lg md:text-xl text-primary-100">
                    Projeniz için hemen bizimle iletişime geçin
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Phone -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                        <i data-lucide="phone" class="w-8 h-8 text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Telefon</h3>
                    <p class="text-secondary-600"><?php echo $settings['contact']['phone']; ?></p>
                </div>
                <!-- Email -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                        <i data-lucide="mail" class="w-8 h-8 text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">E-posta</h3>
                    <p class="text-secondary-600"><?php echo $settings['contact']['email']; ?></p>
                </div>
                <!-- Address -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                        <i data-lucide="map-pin" class="w-8 h-8 text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Adres</h3>
                    <p class="text-secondary-600 whitespace-pre-line"><?php echo $settings['contact']['address']; ?></p>
                </div>
            </div>

            <!-- Contact Form & Map -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Form -->
                <div>
                    <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                        Bize Mesaj Gönderin
                    </h2>

                    <?php
                    $msg = '';
                    $msgType = '';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $name = isset($_POST['name']) ? strip_tags($_POST['name']) : '';
                        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
                        $phone = isset($_POST['phone']) ? strip_tags($_POST['phone']) : '';
                        $subject = isset($_POST['subject']) ? strip_tags($_POST['subject']) : '';
                        $message = isset($_POST['message']) ? strip_tags($_POST['message']) : '';

                        if ($name && $email && $message) {
                            $to = $settings['contact']['email'];
                            $body = "Ad Soyad: $name\n";
                            $body .= "E-posta: $email\n";
                            $body .= "Telefon: $phone\n";
                            $body .= "Konu: $subject\n\n";
                            $body .= "Mesaj:\n$message";

                            $smtpSettings = isset($settings['smtp']) ? $settings['smtp'] : [];
                            $sent = false;

                            // Try SMTP if configured
                            if (!empty($smtpSettings['host'])) {
                                require_once BASE_PATH . '/includes/simple_smtp.php';
                                $smtp = new SimpleSMTP(
                                    $smtpSettings['host'],
                                    $smtpSettings['port'],
                                    $smtpSettings['username'],
                                    $smtpSettings['password'],
                                    $smtpSettings['secure']
                                );
                                
                                // Use configured From address if set, otherwise use sender's email (might be rejected by SPF)
                                // Best practice: From = specific system email, Reply-To = user email
                                $fromEmail = !empty($smtpSettings['fromEmail']) ? $smtpSettings['fromEmail'] : $email;
                                $fromName = !empty($smtpSettings['fromName']) ? $smtpSettings['fromName'] : $name;
                                
                                // Reset body to include sender info if we change From header
                                if ($fromEmail !== $email) {
                                    $body = "Gönderen: $name <$email>\n\n" . $body;
                                }

                                $sent = $smtp->send($to, "İletişim Formu: $subject", $body, $fromEmail, $fromName);
                            } else {
                                // Fallback to mail()
                                $headers = "From: $name <$email>\r\n";
                                $headers .= "Reply-To: $name <$email>\r\n";
                                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                                $sent = mail($to, "İletişim Formu: $subject", $body, $headers);
                            }

                            if ($sent) {
                                $msg = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.';
                                $msgType = 'success';
                            } else {
                                $msg = 'Mesaj gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.';
                                $msgType = 'error';
                            }
                        } else {
                            $msg = 'Lütfen zorunlu alanları doldurunuz.';
                            $msgType = 'error';
                        }
                    }
                    ?>

                    <?php if ($msg): ?>
                        <div class="mb-6 p-4 rounded-lg <?php echo $msgType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'; ?>">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-secondary-700 mb-2">Ad Soyad *</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all" placeholder="Adınız ve soyadınız">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-secondary-700 mb-2">E-posta *</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all" placeholder="ornek@email.com">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-secondary-700 mb-2">Telefon</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all" placeholder="0XXX XXX XX XX">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-secondary-700 mb-2">Konu *</label>
                            <select id="subject" name="subject" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all">
                                <option value="">Konu seçiniz</option>
                                <option value="Genel Bilgi">Genel Bilgi</option>
                                <option value="Teklif Talebi">Teklif Talebi</option>
                                <option value="Teknik Destek">Teknik Destek</option>
                                <option value="İş Birliği">İş Birliği</option>
                                <option value="Diğer">Diğer</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-secondary-700 mb-2">Mesajınız *</label>
                            <textarea id="message" name="message" required rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all resize-none" placeholder="Mesajınızı buraya yazın..."></textarea>
                        </div>

                        <button type="submit" class="w-full px-8 py-3 bg-primary-700 text-white font-bold rounded-lg hover:bg-primary-800 transition-colors flex items-center justify-center gap-2">
                             Gönder <i data-lucide="send" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>

                <!-- Map -->
                <div>
                    <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                        Ofisimiz
                    </h2>
                    <div class="rounded-xl overflow-hidden shadow-lg h-[600px] bg-gray-200 relative">
                        <?php if (!empty($settings['contact']['mapUrl'])): ?>
                            <iframe 
                                src="<?php echo $settings['contact']['mapUrl']; ?>" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                class="absolute inset-0 w-full h-full"
                            ></iframe>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-secondary-600">
                                <div class="text-center">
                                    <i data-lucide="map-pin" class="w-16 h-16 mx-auto mb-4 text-primary-600"></i>
                                    <p>Harita bilgisi yükleniyor...</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Working Hours -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-display font-bold text-secondary-800 mb-8 text-center">
                    Çalışma Saatlerimiz
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-xl font-semibold text-secondary-800 mb-4">Ofis Saatleri</h3>
                        <div class="space-y-2 text-secondary-600">
                            <div class="flex justify-between">
                                <span>Pazartesi - Cuma:</span>
                                <span class="font-medium"><?php echo get_value($settings, 'officeHours.weekdays', '09:00 - 18:00'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Cumartesi:</span>
                                <span class="font-medium"><?php echo get_value($settings, 'officeHours.saturday', '09:00 - 18:00'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pazar:</span>
                                <span class="font-medium"><?php echo get_value($settings, 'officeHours.sunday', 'Kapalı'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-xl font-semibold text-secondary-800 mb-4">Teknik Destek</h3>
                        <div class="space-y-2 text-secondary-600">
                            <div class="flex justify-between">
                                <span>7/24 Destek:</span>
                                <span class="font-medium text-primary-600">Aktif</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Acil Durum:</span>
                                <span class="font-medium"><?php echo $settings['contact']['gsm']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
