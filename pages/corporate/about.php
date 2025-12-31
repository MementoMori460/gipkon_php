<?php
// pages/corporate/about.php
require_once __DIR__ . '/../../functions.php';
render_header();
?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-3xl md:text-5xl font-display font-bold mb-6">Hakkımızda</h1>
                <p class="text-lg md:text-xl text-primary-100">
                    Teknolojiye ve Geleceğe Yön Veren Firma
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-display font-bold text-secondary-800 mb-6">
                        Kimiz?
                    </h2>
                    <div class="space-y-4 text-secondary-600">
                        <p>
                            Firmamız kurulduğu yıldan itibaren sürekli kendini geliştiren, yenilikleri
                            takip eden ve bu doğrultuda hizmet veren otomasyon çözüm firmasıdır.
                            Müşteri memnuniyetini ilke edinerek yolumuza devam etmekteyiz.
                        </p>
                        <p>
                            Yerli ve yabancı otomasyon ürün markaları ile, otomasyon çözümlerini uygun
                            fiyatlarla müşterilerimize ulaştırıyoruz. Siz müşterilerimizin bize olan
                            güvenini boşa çıkartmamak için her geçen gün büyüyerek yolumuza devam
                            etmekteyiz.
                        </p>
                        <p>
                            15+ yıllık deneyimimiz ve uzman kadromuz ile gıda, tekstil, sağlık, kimya,
                            ilaç, kozmetik, enerji, maden ve savunma sanayi sektörlerinde başarılı
                            projeler gerçekleştirdik.
                        </p>
                    </div>
                </div>
                <div class="relative h-96 rounded-xl overflow-hidden shadow-xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                        <div class="text-center text-white">
                            <div class="text-6xl font-bold mb-4">15+</div>
                            <div class="text-2xl">Yıllık Deneyim</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-display font-bold text-secondary-800 mb-4">
                    Değerlerimiz
                </h2>
                <p class="text-lg text-secondary-600 max-w-2xl mx-auto">
                    İş yapış şeklimizi ve kültürümüzü şekillendiren temel değerlerimiz
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Misyonumuz -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="target" class="w-12 h-12 text-primary-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Misyonumuz</h3>
                    <p class="text-secondary-600">Müşterilerimize en yüksek kalitede otomasyon çözümleri sunarak üretim süreçlerini optimize etmek ve rekabet gücünü artırmak.</p>
                </div>
                 <!-- Vizyonumuz -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="eye" class="w-12 h-12 text-primary-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Vizyonumuz</h3>
                    <p class="text-secondary-600">Endüstriyel otomasyon alanında Türkiye'nin lider firması olmak ve uluslararası pazarda güçlü bir marka haline gelmek.</p>
                </div>
                 <!-- Kalite -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="award" class="w-12 h-12 text-primary-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Kalite</h3>
                    <p class="text-secondary-600">Her projede mükemmellik standartlarını koruyarak müşteri memnuniyetini en üst seviyede tutmak.</p>
                </div>
                 <!-- Müşteri Odaklılık -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="users" class="w-12 h-12 text-primary-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Müşteri Odaklılık</h3>
                    <p class="text-secondary-600">Müşterilerimizin ihtiyaçlarını anlamak ve onlara özel çözümler geliştirmek bizim önceliğimizdir.</p>
                </div>
                 <!-- İnovasyon -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="zap" class="w-12 h-12 text-primary-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">İnovasyon</h3>
                    <p class="text-secondary-600">Teknolojideki gelişmeleri yakından takip ederek sürekli yenilikçi çözümler üretiyoruz.</p>
                </div>
                 <!-- Güvenilirlik -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="shield" class="w-12 h-12 text-primary-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-secondary-800 mb-3">Güvenilirlik</h3>
                    <p class="text-secondary-600">Projelerimizde güvenlik ve sürdürülebilirlik ilkelerini ön planda tutuyoruz.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-secondary-800 mb-4">
                    Tarihçemiz
                </h2>
                <p class="text-lg text-secondary-600">
                    Başarı dolu yolculuğumuzun kilometre taşları
                </p>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary-200"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-12">
                        <!-- 2008 -->
                        <div class="flex flex-col md:flex-row items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-0 md:pr-8 md:text-right text-left mb-4 md:mb-0">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2008</div>
                                <div class="text-secondary-600">GIPKON TEKNOLOJİ kuruldu</div>
                            </div>
                            <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                            <div class="hidden md:block w-1/2 pl-8 text-left"></div>
                        </div>
                        
                        <!-- 2010 -->
                         <div class="flex flex-col md:flex-row-reverse items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-8 text-left">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2010</div>
                                <div class="text-secondary-600">İlk büyük ölçekli proje tamamlandı</div>
                            </div>
                             <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                            <div class="hidden md:block w-1/2 md:pr-8 md:text-right"></div>
                        </div>

                        <!-- 2013 -->
                        <div class="flex flex-col md:flex-row items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-0 md:pr-8 md:text-right text-left mb-4 md:mb-0">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2013</div>
                                <div class="text-secondary-600">Uluslararası sertifikalar alındı</div>
                            </div>
                            <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                            <div class="hidden md:block w-1/2 pl-8 text-left"></div>
                        </div>

                         <!-- 2015 -->
                         <div class="flex flex-col md:flex-row-reverse items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-8 text-left">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2015</div>
                                <div class="text-secondary-600">Yeni ofis ve AR-GE merkezi açıldı</div>
                            </div>
                            <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                            <div class="hidden md:block w-1/2 md:pr-8 md:text-right"></div>
                        </div>

                         <!-- 2018 -->
                        <div class="flex flex-col md:flex-row items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-0 md:pr-8 md:text-right text-left mb-4 md:mb-0">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2018</div>
                                <div class="text-secondary-600">500+ proje tamamlandı</div>
                            </div>
                            <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                             <div class="hidden md:block w-1/2 pl-8 text-left"></div>
                        </div>

                         <!-- 2020 -->
                         <div class="flex flex-col md:flex-row-reverse items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-8 text-left">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2020</div>
                                <div class="text-secondary-600">Avrupa pazarına açıldı</div>
                            </div>
                            <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                             <div class="hidden md:block w-1/2 md:pr-8 md:text-right"></div>
                        </div>

                         <!-- 2023 -->
                        <div class="flex flex-col md:flex-row items-center relative">
                            <div class="w-full md:w-1/2 pl-12 md:pl-0 md:pr-8 md:text-right text-left mb-4 md:mb-0">
                                <div class="text-2xl font-bold text-primary-600 mb-2">2023</div>
                                <div class="text-secondary-600">1000+ proje milestonuna ulaşıldı</div>
                            </div>
                             <div class="absolute left-4 md:left-1/2 transform -translate-x-1/2 z-10 flex items-center justify-center">
                                <div class="w-4 h-4 bg-primary-600 rounded-full border-4 border-white shadow"></div>
                            </div>
                             <div class="hidden md:block w-1/2 pl-8 text-left"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary-700 to-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-display font-bold mb-6">
                Projeniz İçin Bizimle İletişime Geçin
            </h2>
            <p class="text-xl mb-8 text-primary-100 max-w-2xl mx-auto">
                Uzman ekibimiz size en uygun otomasyon çözümünü sunmak için hazır
            </p>
            <a href="/iletisim" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors bg-white text-primary-700 hover:bg-gray-100 h-11 px-8">
                İletişime Geç
            </a>
        </div>
    </section>
</main>

<script>lucide.createIcons();</script>

<?php render_footer(); ?>
