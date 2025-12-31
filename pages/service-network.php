<?php
// pages/service-network.php
require_once __DIR__ . '/../functions.php';
render_header();

$serviceRegions = [
    [
        "region" => "Marmara Bölgesi",
        "center" => "İstanbul (Merkez)",
        "address" => "Organize Sanayi Bölgesi, 4. Cadde No: 12, Başakşehir, İstanbul",
        "phone" => "+90 212 999 99 99",
        "coverage" => ["İstanbul", "Kocaeli", "Bursa", "Tekirdağ", "Sakarya"]
    ],
    [
        "region" => "Ege Bölgesi",
        "center" => "İzmir",
        "address" => "Atatürk Organize Sanayi Bölgesi, 10003 Sokak No: 15, Çiğli, İzmir",
        "phone" => "+90 232 999 99 99",
        "coverage" => ["İzmir", "Manisa", "Aydın", "Denizli"]
    ],
    [
        "region" => "İç Anadolu Bölgesi",
        "center" => "Ankara",
        "address" => "OSTİM Organize Sanayi Bölgesi, 1234. Sk. No: 45, Yenimahalle, Ankara",
        "phone" => "+90 312 999 99 99",
        "coverage" => ["Ankara", "Konya", "Eskişehir", "Kayseri"]
    ],
    [
        "region" => "Güney Anadolu Bölgesi",
        "center" => "Gaziantep",
        "address" => "2. Organize Sanayi Bölgesi, 83204 Nolu Cadde No: 5, Şehitkamil, Gaziantep",
        "phone" => "+90 342 999 99 99",
        "coverage" => ["Gaziantep", "Adana", "Mersin", "Hatay"]
    ]
];
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-primary-900 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900 to-primary-800 opacity-90 z-0"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">
                Servis Ağımız
            </h1>
            <p class="text-xl text-primary-200 max-w-2xl mx-auto">
                Türkiye genelinde yaygın servis ağımızla 7/24 yanınızdayız
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="clock" class="w-8 h-8 text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-secondary-800 mb-3">7/24 Destek</h3>
                    <p class="text-secondary-600">Her an ulaşabileceğiniz teknik destek ekibimizle üretiminiz hiç durmasın.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="wrench" class="w-8 h-8 text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-secondary-800 mb-3">Yerinde Müdahale</h3>
                    <p class="text-secondary-600">Gezici teknik servis araçlarımızla en kısa sürede yerinde arıza tespiti ve onarım.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="shield" class="w-8 h-8 text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-secondary-800 mb-3">Garantili Yedek Parça</h3>
                    <p class="text-secondary-600">Orijinal yedek parça ve garantili işçilik ile sistemleriniz güvende.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map/Regions Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-display font-bold text-secondary-800 mb-4">
                    Bölge Müdürlüklerimiz
                </h2>
                <p class="text-secondary-600 max-w-2xl mx-auto">
                    Sektörün ihtiyacına yönelik spesifik çözümlerimizle GIPKON'un tecrübeli servis ekibi 7/24 hizmetinizde.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <?php foreach ($serviceRegions as $region): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                        <h3 class="text-2xl font-bold text-primary-700 mb-6 border-b border-gray-100 pb-4">
                            <?php echo $region['region']; ?>
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i data-lucide="map-pin" class="w-5 h-5 text-secondary-400 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <div class="font-semibold text-secondary-800"><?php echo $region['center']; ?></div>
                                    <div class="text-sm text-secondary-600"><?php echo $region['address']; ?></div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="phone" class="w-5 h-5 text-secondary-400 mr-3 flex-shrink-0"></i>
                                <a href="tel:<?php echo $region['phone']; ?>" class="text-primary-600 font-medium hover:underline">
                                    <?php echo $region['phone']; ?>
                                </a>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg mt-4">
                                <span class="text-xs font-semibold text-secondary-500 uppercase tracking-wide block mb-2">
                                    Hizmet Verilen İller
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($region['coverage'] as $city): ?>
                                        <span class="text-sm px-2 py-1 bg-white border border-gray-200 rounded text-secondary-600">
                                            <?php echo $city; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-primary-700 text-white text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-6">Acil Teknik Destek</h2>
            <p class="text-xl text-primary-100 mb-8">
                Teknik bir sorun mu yaşıyorsunuz? 7/24 destek hattımız hizmetinizde.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/hizmet-talebi" class="inline-block px-8 py-3 bg-white text-primary-700 font-bold rounded-lg hover:bg-gray-100 transition-colors">
                    Servis Talebi Oluştur
                </a>
                <a href="tel:<?php echo $settings['contact']['phone']; ?>" class="inline-flex items-center justify-center px-8 py-3 bg-transparent border border-white text-white font-bold rounded-lg hover:bg-white/10 transition-colors">
                    <i data-lucide="phone" class="w-5 h-5 mr-2"></i>
                    Hemen Ara
                </a>
            </div>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
