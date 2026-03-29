<script>
    App.updateHeader('<a href="#agent_dashboard" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></a>', '<button onclick="openMerchantModal()" class="neon-button px-8 py-4 text-[11px]"><i class="fas fa-plus mr-2"></i> Register Vendor</button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">

    <!-- Hero Header -->
    <div class="px-4">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1.5 h-1.5 bg-teal-500 rounded-full animate-pulse shadow-[0_0_10px_#14b8a6]"></span>
            <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Supplier Nexus</h3>
        </div>
        <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Vendor <br><span class="text-teal-500">Directory</span></h2>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 px-4">
        <div class="quantum-glass p-8 rounded-[36px] border border-slate-50 flex flex-col gap-2">
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Total Nodes</span>
            <span class="text-3xl font-black text-slate-900" id="stat-merchants">--</span>
        </div>
        <div class="quantum-glass p-8 rounded-[36px] border border-teal-100/50 bg-teal-50/20 flex flex-col gap-2">
            <span class="text-[10px] font-black text-teal-500 uppercase tracking-widest">Verified</span>
            <span class="text-3xl font-black text-slate-900">Active</span>
        </div>
    </div>

    <!-- Merchants Grid -->
    <div id="am-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4 slide-up" style="animation-delay: 0.1s;">
        <!-- Loader -->
        <div class="quantum-card p-10 animate-pulse bg-white border border-slate-50 flex items-center gap-8">
            <div class="w-20 h-20 bg-slate-50 rounded-[28px]"></div>
            <div class="flex-1 space-y-4">
                <div class="h-5 bg-slate-50 rounded-xl w-1/2"></div>
                <div class="h-4 bg-slate-50 rounded-xl w-2/3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadVendorDirectory() {
        const res = await API.request('agent.php?action=my_merchants');
        if (res.status === 200 && res.data.success) {
            const merchants = res.data.merchants;
            document.getElementById('stat-merchants').textContent = merchants.length;

            if (merchants.length === 0) {
                document.getElementById('am-list').innerHTML = `
                <div class="col-span-full py-40 quantum-glass rounded-[48px] border border-slate-100 flex flex-col items-center gap-8 group">
                    <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-inner">
                        <i class="fas fa-store text-6xl"></i>
                    </div>
                    <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.5em]">No vendor nodes registered</p>
                    <button onclick="openMerchantModal()" class="neon-button px-10 py-6 text-[11px]" style="background:var(--quantum-slate)"><i class="fas fa-plus mr-2"></i> Register First Vendor</button>
                </div>`;
                return;
            }

            document.getElementById('am-list').innerHTML = merchants.map((m, idx) => `
            <div class="quantum-card p-10 group hover:border-teal-500/30 relative overflow-hidden flex items-center gap-6" style="animation-delay: ${idx * 0.05}s">
                <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-[24px] flex items-center justify-center text-xl font-black shadow-inner border border-teal-100 group-hover:scale-110 group-hover:rotate-[-8deg] transition-all flex-shrink-0">
                    ${m.name.charAt(0).toUpperCase()}
                </div>

                <div class="flex-1 min-w-0 space-y-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[8px] font-black text-teal-500 bg-teal-50 px-3 py-1 rounded-[10px] border border-teal-100 uppercase tracking-widest">Verified Node</span>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">ID: VX-${m.id}</span>
                    </div>
                    <h4 class="text-[18px] font-black text-slate-950 tracking-tight truncate">${m.name}</h4>
                    <p class="text-[12px] font-bold text-slate-400 truncate flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-teal-400"></i> ${m.location || 'Guangzhou, China'}
                    </p>
                </div>

                <div class="flex flex-col gap-2">
                    <button onclick="viewMerchant(${m.id})" class="w-10 h-10 quantum-glass rounded-xl flex items-center justify-center text-slate-300 hover:text-teal-500 transition-all active:scale-95">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
            `).join('');
        }
    }

    window.openMerchantModal = () => {
        App.showModal(`
        <div class="p-6 space-y-6">
            <div class="space-y-1">
                <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Register Vendor</h3>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Supplier Node Genesis</p>
            </div>
            <form id="frm-mcnt" onsubmit="submitMerchant(event)" class="space-y-5">
                <input type="text" id="m-name" required placeholder="Supplier Designation" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-teal-500/50 transition-all outline-none">
                <input type="text" id="m-contact" placeholder="Contact Link (WeChat / Phone)" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-teal-500/50 transition-all outline-none">
                <input type="text" id="m-location" placeholder="Operational Node (City)" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-teal-500/50 transition-all outline-none">
                <textarea id="m-notes" rows="2" placeholder="Supplier intelligence notes..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-[24px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-teal-500/50 transition-all outline-none resize-none"></textarea>
                <button type="submit" class="neon-button w-full py-5" style="background:var(--quantum-slate)">Initialize Vendor Node</button>
            </form>
        </div>`);
    };

    window.submitMerchant = async (e) => {
        e.preventDefault();
        const data = {
            name: document.getElementById('m-name').value,
            contact: document.getElementById('m-contact').value,
            location: document.getElementById('m-location').value,
            notes: document.getElementById('m-notes').value,
        };
        const res = await API.request('agent.php?action=add_merchant', 'POST', data);
        if (res.status === 200 && res.data.success) {
            showToast('Vendor Node Registered', 'success');
            App.hideModal();
            loadVendorDirectory();
        } else {
            showToast(res.data.message || 'Registration failed', 'error');
        }
    };

    window.viewMerchant = (id) => showToast(`Vendor VX-${id} profile syncing...`, 'success');

    loadVendorDirectory();
</script>
