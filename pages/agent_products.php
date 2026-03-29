<script>
    App.updateHeader('<a href="#agent_dashboard" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></a>', '<button onclick="openProductModal()" class="neon-button px-8 py-4 text-[11px]"><i class="fas fa-plus mr-2"></i> Deploy Asset</button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">

    <!-- Hero Header -->
    <div class="px-4">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_#10b981]"></span>
            <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Asset Management Nexus</h3>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Asset <br><span class="text-emerald-500">Vault</span></h2>
            <button onclick="openProductModal()" class="md:hidden neon-button px-10 py-6 w-full">
                <i class="fas fa-plus mr-3"></i> Deploy New Asset
            </button>
        </div>
    </div>

    <!-- Product Grid -->
    <div id="ap-list" class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8 px-4 slide-up" style="animation-delay: 0.1s;">
        <!-- Loaders -->
        <div class="quantum-card aspect-[10/14] p-6 animate-pulse bg-white border border-slate-50 flex flex-col gap-6">
            <div class="w-full h-1/2 bg-slate-50 rounded-[28px]"></div>
            <div class="space-y-4">
                <div class="h-5 bg-slate-50 rounded-xl w-3/4"></div>
                <div class="h-4 bg-slate-50 rounded-xl w-1/2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadAssetVault() {
        const res = await API.request('agent.php?action=my_products');
        if (res.status === 200 && res.data.success) {
            const products = res.data.products;
            if (products.length === 0) {
                document.getElementById('ap-list').innerHTML = `
                <div class="col-span-full py-40 quantum-glass rounded-[48px] border border-slate-100 flex flex-col items-center gap-8 group">
                    <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-inner">
                        <i class="fas fa-box-open text-6xl"></i>
                    </div>
                    <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.5em]">Asset Vault is empty</p>
                    <button onclick="openProductModal()" class="neon-button px-10 py-6 text-[11px]"><i class="fas fa-plus mr-2"></i> Deploy First Asset</button>
                </div>`;
                return;
            }
            document.getElementById('ap-list').innerHTML = products.map((p, idx) => `
            <div class="quantum-card group/item relative overflow-hidden flex flex-col" style="animation-delay: ${idx * 0.05}s">
                <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-b from-transparent to-slate-950/70 opacity-0 group-hover/item:opacity-100 transition-opacity duration-500 z-10 rounded-[32px]"></div>

                <div class="aspect-square w-full overflow-hidden bg-slate-50 rounded-t-[32px] relative">
                    ${p.image_url ? `<img src="${p.image_url}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-[1.5s]">` : `<div class="w-full h-full flex items-center justify-center text-slate-100"><i class="fas fa-cube text-6xl opacity-20"></i></div>`}
                    <div class="absolute top-4 right-4 z-20 flex gap-2">
                        <button onclick="event.stopPropagation(); editProduct(${p.id})" class="w-9 h-9 quantum-glass rounded-xl flex items-center justify-center text-white hover:text-emerald-400 transition-colors backdrop-blur-xl"><i class="fas fa-pen text-xs"></i></button>
                        <button onclick="event.stopPropagation(); deleteProduct(${p.id})" class="w-9 h-9 quantum-glass rounded-xl flex items-center justify-center text-white hover:text-rose-400 transition-colors backdrop-blur-xl"><i class="fas fa-trash text-xs"></i></button>
                    </div>
                </div>

                <div class="p-6 flex flex-col flex-1">
                    <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-2">NBX-0${p.id}</p>
                    <h4 class="text-[15px] font-black text-slate-950 tracking-tight leading-tight truncate mb-4">${p.name}</h4>
                    <div class="mt-auto flex items-center justify-between">
                        <p class="text-xl font-black text-slate-900 tracking-tighter">₦${parseFloat(p.price_naira).toLocaleString()}</p>
                        <span class="text-[8px] font-black ${p.in_stock ? 'text-emerald-500 bg-emerald-50' : 'text-rose-500 bg-rose-50'} px-3 py-1.5 rounded-lg border border-current/20">${p.in_stock ? 'Live' : 'Offline'}</span>
                    </div>
                </div>
            </div>
            `).join('');
        }
    }

    window.openProductModal = () => {
        App.showModal(`
        <div class="p-6 space-y-6">
            <div class="space-y-1">
                <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Deploy Asset</h3>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Quantum Registry Sync</p>
            </div>
            <form id="frm-prod" onsubmit="submitProduct(event)" class="space-y-5">
                <input type="text" id="p-name" required placeholder="Asset Designation" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500/50 transition-all outline-none">
                <textarea id="p-desc" rows="2" placeholder="Asset Intelligence Report..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500/50 transition-all outline-none resize-none"></textarea>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" id="p-price-cny" required min="0" step="0.01" placeholder="CNY Price" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500/50 transition-all outline-none">
                    <input type="number" id="p-price-ngn" required min="0" step="0.01" placeholder="NGN Price" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500/50 transition-all outline-none">
                </div>
                <input type="text" id="p-category" placeholder="Sector / Category" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500/50 transition-all outline-none">
                <input type="url" id="p-image" placeholder="Asset Image URL" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500/50 transition-all outline-none">
                <button type="submit" class="neon-button w-full py-5">Activate Deployment</button>
            </form>
        </div>`);
    };

    window.submitProduct = async (e) => {
        e.preventDefault();
        const data = {
            name: document.getElementById('p-name').value,
            description: document.getElementById('p-desc').value,
            price_cny: document.getElementById('p-price-cny').value,
            price_naira: document.getElementById('p-price-ngn').value,
            category: document.getElementById('p-category').value,
            image_url: document.getElementById('p-image').value,
        };
        const res = await API.request('agent.php?action=add_product', 'POST', data);
        if (res.status === 200 && res.data.success) {
            showToast('Asset Deployed to Quantum Registry', 'success');
            App.hideModal();
            loadAssetVault();
        } else {
            showToast(res.data.message || 'Deployment failed', 'error');
        }
    };

    window.editProduct = (id) => showToast(`Asset NBX-0${id} editor coming soon`, 'success');
    window.deleteProduct = async (id) => {
        const res = await API.request(`agent.php?action=delete_product&id=${id}`, 'DELETE');
        if (res.status === 200 && res.data.success) {
            showToast('Asset decommissioned', 'success');
            loadAssetVault();
        } else {
            showToast('Decommission failed', 'error');
        }
    };

    loadAssetVault();
</script>
