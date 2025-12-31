<?php
// pages/corporate/hr.php
require_once __DIR__ . '/../../functions.php';
render_header();

$benefits = [
    [
        'icon' => 'graduation-cap',
        'title' => 'Sürekli Gelişim',
        'description' => 'Düzenli teknik eğitimler ve kişisel gelişim fırsatları.'
    ],
    [
        'icon' => 'heart',
        'title' => 'Tamamlayıcı Sağlık',
        'description' => 'Tüm çalışanlarımız için kapsamlı özel sağlık sigortası.'
    ],
    [
        'icon' => 'users',
        'title' => 'Güçlü İletişim',
        'description' => 'Açık iletişim kültürü ve sosyal aktiviteler.'
    ],
    [
        'icon' => 'target',
        'title' => 'Kariyer Yolu',
        'description' => 'Net kariyer planlaması ve yükselme fırsatları.'
    ]
];
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-900 to-primary-800 text-white py-12 md:py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-5xl font-display font-bold mb-6">
                Kariyer Fırsatları
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
                Teknolojiye yön veren dinamik ekibimizin bir parçası olmak ister misiniz?
            </p>
        </div>
    </section>

    <!-- Culture Section -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-12 max-w-6xl mx-auto">
                <div class="flex-1">
                    <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                        Çalışma Kültürümüz
                    </h2>
                    <p class="text-secondary-600 text-lg mb-6 leading-relaxed">
                        GIPKON TEKNOLOJİ olarak, çalışanlarımızın mutluluğu ve gelişimi bizim için önceliklidir.
                        Yenilikçi fikirlerin değer gördüğü, takım çalışmasının teşvik edildiği ve sürekli
                        öğrenmenin desteklendiği bir çalışma ortamı sunuyoruz.
                    </p>
                    <div class="space-y-4">
                        <?php 
                        $items = [
                            "Yenilikçi ve dinamik çalışma ortamı",
                            "Teknoloji odaklı projeler",
                            "Liyakat esaslı değerlendirme",
                            "Eşitlikçi ve adil yaklaşım"
                        ];
                        foreach ($items as $item): ?>
                            <div class="flex items-center text-secondary-700">
                                <i data-lucide="check-circle" class="w-5 h-5 text-primary-600 mr-3"></i>
                                <?php echo $item; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="flex-1 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($benefits as $benefit): ?>
                            <div class="bg-gray-50 p-6 rounded-xl hover:bg-gray-100 transition-colors">
                                <i data-lucide="<?php echo $benefit['icon']; ?>" class="w-10 h-10 text-primary-600 mb-4"></i>
                                <h3 class="font-bold text-secondary-800 mb-2"><?php echo $benefit['title']; ?></h3>
                                <p class="text-sm text-secondary-600"><?php echo $benefit['description']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Open Positions -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <h2 class="text-3xl font-display font-bold text-secondary-800 mb-4">
                    Açık Pozisyonlar
                </h2>
                <p class="text-secondary-600">
                    Şu anda açık pozisyonumuz bulunmamaktadır. Ancak genel başvurularınızı değerlendirmek üzere her zaman kabul ediyoruz.
                </p>
            </div>

            <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="briefcase" class="w-8 h-8 text-primary-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-secondary-800 mb-6 text-center">
                    Genel Başvuru Formu
                </h3>

                <form class="space-y-6 text-left" onsubmit="event.preventDefault(); alert('Başvurunuz alındı (Demo).');">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Ad Soyad *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Telefon *</label>
                            <input type="tel" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">E-posta *</label>
                            <input type="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Pozisyon / Alan</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                                <option value="">Seçiniz</option>
                                <option value="yazilim">Yazılım / Otomasyon</option>
                                <option value="elektrik">Elektrik / Elektronik</option>
                                <option value="satis">Satış / Pazarlama</option>
                                <option value="idari">İdari İşler</option>
                                <option value="staj">Stajyer</option>
                                <option value="diger">Diğer</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kısa Ön Yazı</label>
                        <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none resize-none" placeholder="Kendinizden kısaca bahsedin..."></textarea>
                    </div>

                    <div>
                         <label class="block text-sm font-medium text-secondary-700 mb-2">CV Yükle (PDF)</label>
                         <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer">
                            <p class="text-secondary-500 text-sm">Dosyayı buraya sürükleyin veya seçmek için tıklayın</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-8 py-3 bg-primary-600 text-white font-bold rounded-lg hover:bg-primary-700 transition-colors">
                        Başvuruyu Gönder
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
