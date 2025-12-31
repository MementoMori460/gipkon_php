<?php
// pages/request.php
require_once __DIR__ . '/../functions.php';
render_header();

$requestTypes = [
    [
        "id" => "teklif",
        "title" => "Teklif İste",
        "icon" => "file-text",
        "description" => "Yeni proje ve sistem ihtiyaçlarınız için teklif alın."
    ],
    [
        "id" => "servis",
        "title" => "Servis/Bakım",
        "icon" => "wrench",
        "description" => "Arıza bildirimi veya periyodik bakım talebi oluşturun."
    ],
    [
        "id" => "yedek-parca",
        "title" => "Yedek Parça",
        "icon" => "settings",
        "description" => "İhtiyacınız olan yedek parçalar için talep oluşturun."
    ],
    [
        "id" => "egitim",
        "title" => "Eğitim Talebi",
        "icon" => "book-open",
        "description" => "Teknik personeliniz için eğitim programı talep edin."
    ]
];
?>

<style>
    .type-btn.active {
        background-color: white;
        border-color: var(--primary-500);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .type-btn.active .icon-container {
        background-color: var(--primary-100);
        color: var(--primary-600);
    }
    .type-btn.active h3 {
        color: var(--primary-700);
    }
</style>

<main class="min-h-screen bg-gray-50 py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-display font-bold text-secondary-800 mb-4">
                Hizmet Talebi Oluşturun
            </h1>
            <p class="text-xl text-secondary-600 max-w-2xl mx-auto">
                İhtiyacınız olan hizmet türünü seçin ve formu doldurun. Uzman ekibimiz en kısa sürede size dönüş yapacaktır.
            </p>
        </div>

        <!-- Type Selection Tabs -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto mb-12">
            <?php foreach ($requestTypes as $index => $type): ?>
                <button
                    onclick="selectType('<?php echo $type['id']; ?>', '<?php echo $type['title']; ?>', this)"
                    class="type-btn p-6 rounded-xl border border-gray-200 transition-all text-left bg-white hover:border-primary-300 hover:shadow-sm <?php echo $index === 0 ? 'active' : ''; ?>"
                >
                    <div class="icon-container w-10 h-10 rounded-full flex items-center justify-center mb-3 bg-gray-100 text-gray-500">
                        <i data-lucide="<?php echo $type['icon']; ?>" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold mb-1 text-secondary-700">
                        <?php echo $type['title']; ?>
                    </h3>
                    <p class="text-xs text-secondary-500">
                        <?php echo $type['description']; ?>
                    </p>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Form Section -->
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">
            <div class="mb-8 border-b border-gray-100 pb-6">
                <h2 class="text-2xl font-bold text-secondary-800 flex items-center">
                    <span id="formTitle">Teklif İste</span> Formu
                </h2>
            </div>

            <form action="#" method="POST" class="space-y-6" onsubmit="event.preventDefault(); alert('Talebiniz Alındı! (Demo)');">
                <input type="hidden" name="type" id="typeInput" value="teklif">
                
                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Ad Soyad *</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Firma Adı</label>
                        <input type="text" name="company" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">E-posta *</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Telefon *</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                    </div>
                </div>

                <!-- Specific Fields -->
                <div id="serviceFields" class="hidden">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Makine/Sistem Modeli</label>
                    <input type="text" name="machineModel" placeholder="Varsa seri no veya model bilgisi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none mb-6">
                </div>

                <div id="partFields" class="hidden">
                     <label class="block text-sm font-medium text-secondary-700 mb-2">Parça Kodu (Varsa)</label>
                     <input type="text" name="partNumber" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none mb-6">
                </div>

                 <div id="trainingFields" class="hidden">
                     <label class="block text-sm font-medium text-secondary-700 mb-2">Katılımcı Sayısı</label>
                     <input type="number" name="attendeeCount" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none mb-6">
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Talep Detayları *</label>
                    <textarea name="details" required rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none resize-none" placeholder="Lütfen talebinizi detaylı bir şekilde açıklayınız..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full px-8 py-3 bg-primary-600 text-white font-bold rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center">
                        Talebi Gönder <i data-lucide="send" class="w-5 h-5 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    lucide.createIcons();

    function selectType(id, title, btn) {
        // Update Title
        document.getElementById('formTitle').innerText = title;
        document.getElementById('typeInput').value = id;

        // Visual Active State
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Show/Hide Fields
        document.getElementById('serviceFields').classList.add('hidden');
        document.getElementById('partFields').classList.add('hidden');
        document.getElementById('trainingFields').classList.add('hidden');

        if (id === 'servis' || id === 'yedek-parca') {
            document.getElementById('serviceFields').classList.remove('hidden');
        }
        if (id === 'yedek-parca') {
            document.getElementById('partFields').classList.remove('hidden');
        }
        if (id === 'egitim') {
            document.getElementById('trainingFields').classList.remove('hidden');
        }
    }
</script>

<?php render_footer(); ?>
