<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

require_once __DIR__ . '/../../functions.php';

$menu = load_json('menu');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = isset($_POST['menu_json']) ? $_POST['menu_json'] : '';
    $decoded = json_decode($json, true);
    
    if ($decoded) {
        save_json('menu', $decoded);
        header('Location: /admin/menu?msg=saved');
        exit;
    }
}

render_header();
?>

<div class="flex min-h-screen bg-gray-50">
    <?php include BASE_PATH . '/includes/admin_sidebar.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <form method="POST" id="menuForm">
            <input type="hidden" name="menu_json" id="menuJsonInput">
            
            <div class="flex justify-between items-center mb-8 bg-gray-50/95 sticky top-0 z-10 py-4 border-b border-gray-200 backdrop-blur-sm px-1">
                <h1 class="text-2xl font-bold text-gray-800">Menü Yönetimi</h1>
                <button type="submit" class="flex items-center gap-2 bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Kaydet
                </button>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">Menü başarıyla güncellendi.</span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-8 max-w-4xl" id="menuApp">
                <!-- Rendered by JS -->
                <div class="text-center py-10">Yükleniyor...</div>
            </div>
        </form>
    </main>
</div>

<script>
    // Initial Data
    let menuData = <?php echo json_encode($menu); ?>;
    
    // Ensure structure
    if(!menuData.header) menuData.header = [];
    if(!menuData.footer) menuData.footer = { quickLinks: [] };

    const app = document.getElementById('menuApp');
    const input = document.getElementById('menuJsonInput');

    function render() {
        app.innerHTML = '';
        input.value = JSON.stringify(menuData);

        // --- HEADER SECTION ---
        const headerSection = document.createElement('div');
        headerSection.className = 'bg-white p-6 rounded-xl shadow-sm border border-gray-100';
        headerSection.innerHTML = `<h2 class="text-lg font-semibold mb-4 pb-2 border-b">Üst Menü (Header)</h2>`;
        
        const headerList = document.createElement('div');
        headerList.className = 'space-y-4';
        
        menuData.header.forEach((item, index) => {
            const itemEl = document.createElement('div');
            itemEl.className = 'border border-gray-100 rounded-lg p-4 bg-gray-50/50';
            
            // Top Level Fields
            const topRow = document.createElement('div');
            topRow.className = 'flex items-center gap-4';
            topRow.innerHTML = `
                <div class="flex-1 gap-4 flex">
                    <input type="text" value="${item.name || ''}" class="border border-gray-300 px-3 py-1.5 rounded w-1/3 text-sm" placeholder="Menü Adı" oninput="updateHeader(${index}, 'name', this.value)">
                    ${item.items ? 
                        `<input type="text" disabled value="Alt Menü Var" class="border border-gray-200 bg-gray-100 px-3 py-1.5 rounded w-1/3 text-sm text-gray-400 italic">` :
                        `<input type="text" value="${item.href || ''}" class="border border-gray-300 px-3 py-1.5 rounded w-1/3 text-sm font-mono" placeholder="URL" oninput="updateHeader(${index}, 'href', this.value)">`
                    }
                </div>
                <div class="flex items-center gap-2">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" ${item.active ? 'checked' : ''} onchange="updateHeader(${index}, 'active', this.checked)">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                    ${!item.items ? 
                        `<button type="button" onclick="addHeaderSub(${index})" class="p-1 text-blue-600 hover:bg-blue-50 rounded" title="Alt Menü Ekle"><i data-lucide="list-plus" class="w-5 h-5"></i></button>` : 
                        `<button type="button" onclick="removeHeaderSub(${index})" class="p-1 text-red-600 hover:bg-red-50 rounded" title="Alt Menüleri Sil"><i data-lucide="list-x" class="w-5 h-5"></i></button>`
                    }
                </div>
            `;
            itemEl.appendChild(topRow);

            // Submenu Items
            if (item.items) {
                const subList = document.createElement('div');
                subList.className = 'mt-4 pl-8 border-l-2 border-gray-200 ml-4 py-2 space-y-3';
                
                item.items.forEach((sub, subIndex) => {
                    const subRow = document.createElement('div');
                    subRow.className = 'flex items-center gap-4';
                    subRow.innerHTML = `
                        <input type="text" value="${sub.name || ''}" class="border border-gray-300 px-3 py-1.5 rounded w-1/3 text-sm" placeholder="Alt Başlık" oninput="updateHeaderSub(${index}, ${subIndex}, 'name', this.value)">
                        <input type="text" value="${sub.href || ''}" class="border border-gray-300 px-3 py-1.5 rounded w-1/3 text-sm font-mono" placeholder="URL" oninput="updateHeaderSub(${index}, ${subIndex}, 'href', this.value)">
                        <div class="flex items-center gap-2">
                             <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" ${sub.active ? 'checked' : ''} onchange="updateHeaderSub(${index}, ${subIndex}, 'active', this.checked)">
                                <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                            <button type="button" onclick="removeHeaderSubItem(${index}, ${subIndex})" class="text-red-500 hover:text-red-700 p-1"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    `;
                    subList.appendChild(subRow);
                });

                // Add Sub Item Button
                const addSubBtn = document.createElement('button');
                addSubBtn.type = 'button';
                addSubBtn.className = 'text-sm text-primary-600 hover:text-primary-700 flex items-center font-medium mt-2';
                addSubBtn.innerHTML = `<i data-lucide="plus" class="w-4 h-4 mr-1"></i> Yeni Alt Link Ekle`;
                addSubBtn.onclick = () => addHeaderSubItem(index);
                subList.appendChild(addSubBtn);

                itemEl.appendChild(subList);
            }
            
            headerList.appendChild(itemEl);
        });

        headerSection.appendChild(headerList);
        app.appendChild(headerSection);


        // --- FOOTER SECTION ---
        const footerSection = document.createElement('div');
        footerSection.className = 'bg-white p-6 rounded-xl shadow-sm border border-gray-100';
        footerSection.innerHTML = `<h2 class="text-lg font-semibold mb-4 pb-2 border-b">Alt Menü (Hızlı Linkler)</h2>`;
        
        const footerList = document.createElement('div');
        footerList.className = 'space-y-3';

        menuData.footer.quickLinks.forEach((item, index) => {
            const footerRow = document.createElement('div');
            footerRow.className = 'flex items-center gap-4 p-2 rounded hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-colors';
            footerRow.innerHTML = `
                <input type="text" value="${item.name || ''}" class="border border-gray-300 px-3 py-1.5 rounded w-1/2 text-sm" placeholder="Link Adı" oninput="updateFooter(${index}, 'name', this.value)">
                <input type="text" value="${item.href || ''}" class="border border-gray-300 px-3 py-1.5 rounded w-1/2 text-sm font-mono" placeholder="URL" oninput="updateFooter(${index}, 'href', this.value)">
                <div class="flex items-center gap-2">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" ${item.active ? 'checked' : ''} onchange="updateFooter(${index}, 'active', this.checked)">
                        <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                    </label>
                    <button type="button" onclick="removeFooterItem(${index})" class="text-red-500 hover:text-red-700 p-1"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </div>
            `;
            footerList.appendChild(footerRow);
        });

        // Add Footer Item Button
        const addFooterBtn = document.createElement('button');
        addFooterBtn.type = 'button';
        addFooterBtn.className = 'text-sm text-primary-600 hover:text-primary-700 flex items-center font-medium mt-4';
        addFooterBtn.innerHTML = `<i data-lucide="plus" class="w-4 h-4 mr-1"></i> Yeni Link Ekle`;
        addFooterBtn.onclick = () => addFooterItem();
        footerSection.appendChild(footerList);
        footerSection.appendChild(addFooterBtn);
        
        app.appendChild(footerSection);

        lucide.createIcons();
    }

    // --- Actions ---

    // Header Actions
    window.updateHeader = (index, key, value) => {
        menuData.header[index][key] = value;
        render(); // Re-render needed for some state changes, or just visual update? 
        // For simplicity, we store value in object. Re-render might lose focus if not careful.
        // Optimization: Don't re-render on simple text input, only updates input value in JSON.
        input.value = JSON.stringify(menuData);
    };

    window.addHeaderSub = (index) => {
        if(!confirm('Bu menü öğesini açılır menüye dönüştürmek istediğinize emin misiniz? Mevcut linki kaldırılacak.')) return;
        menuData.header[index].href = ''; // Clear href
        menuData.header[index].items = []; // Init array
        render();
    };

    window.removeHeaderSub = (index) => {
         if(!confirm('Tüm alt menüleri silmek ve normal linke dönüştürmek istediğinize emin misiniz?')) return;
         delete menuData.header[index].items;
         menuData.header[index].href = '/'; // Default placeholder
         render();
    };

    window.addHeaderSubItem = (parentIndex) => {
        menuData.header[parentIndex].items.push({ name: 'Yeni Link', href: '#', active: true });
        render();
    };

    window.removeHeaderSubItem = (parentIndex, subIndex) => {
        menuData.header[parentIndex].items.splice(subIndex, 1);
        render();
    };

    window.updateHeaderSub = (pIdx, sIdx, key, val) => {
        menuData.header[pIdx].items[sIdx][key] = val;
        input.value = JSON.stringify(menuData);
    };

    // Footer Actions
    window.updateFooter = (index, key, value) => {
        menuData.footer.quickLinks[index][key] = value;
        input.value = JSON.stringify(menuData);
    };

    window.addFooterItem = () => {
        menuData.footer.quickLinks.push({ name: 'Yeni Link', href: '#', active: true });
        render();
    };

    window.removeFooterItem = (index) => {
        menuData.footer.quickLinks.splice(index, 1);
        render();
    };

    // Initial Render
    render();

    // Prevent loose focus on input update (Simple hack: override render for inputs to just update value if needed, 
    // but here we just re-render full list on structural changes, and avoid full re-render on text input)
    // To support re-render on text input without losing focus, we need a better Diff engine (like React), 
    // so for vanilla JS, we simply attached oninput to update data, and only call render() on structure add/remove.
    // However, I used 'oninput="update..."' which calls updateHeader which calls render() in my code above?
    // Wait, checked code: updateHeader calls render(). This WILL cause focus loss.
    // FIX: updateHeader/updateFooter should NOT call render(), only update json input.
    
    // Patching the functions to avoid re-render on text input
    window.updateHeader = (index, key, value) => {
        menuData.header[index][key] = value;
        input.value = JSON.stringify(menuData);
    };
    window.updateHeaderSub = (pIdx, sIdx, key, val) => {
        menuData.header[pIdx].items[sIdx][key] = val;
        input.value = JSON.stringify(menuData);
    };
    window.updateFooter = (index, key, value) => {
        menuData.footer.quickLinks[index][key] = value;
        input.value = JSON.stringify(menuData);
    };

</script>
</body>
</html>
