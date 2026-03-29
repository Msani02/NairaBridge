<script>
    App.updateHeader('<i class="fas fa-microchip text-rose-500"></i> Global Ledger', '<button onclick="App.navigate(\'admin_dashboard\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">
    
    <!-- Hero Header -->
    <div class="px-4 flex flex-col md:flex-row justify-between items-end gap-8">
        <div>
            <div class="flex items-center gap-3 mb-4">
                 <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse shadow-[0_0_10px_#f43f5e]"></span>
                 <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Financial Governance</h3>
            </div>
            <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Capital <br><span class="text-rose-500">Flux</span></h2>
        </div>
        <div class="flex gap-4">
             <button onclick="downloadLedger()" class="w-14 h-14 quantum-glass rounded-[20px] flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-download text-lg"></i></button>
             <button onclick="filterFlux()" class="w-14 h-14 quantum-glass rounded-[20px] flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-filter text-lg"></i></button>
        </div>
    </div>

    <!-- Transaction Flow -->
    <div id="tx-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4 slide-up" style="animation-delay: 0.1s;">
        <!-- Loaders -->
        <div class="quantum-card p-10 animate-pulse bg-white border border-slate-50 flex items-center gap-8">
            <div class="w-16 h-16 bg-slate-50 rounded-[24px]"></div>
            <div class="flex-1 space-y-4">
                <div class="h-5 bg-slate-50 rounded-xl w-1/3"></div>
                <div class="h-4 bg-slate-50 rounded-xl w-1/2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadGlobalFlux() {
        const res = await API.request('admin.php?action=transactions');
        if (res.status === 200 && res.data.success) {
            const txs = res.data.transactions;
            if (txs.length === 0) {
                document.getElementById('tx-list').innerHTML = `
                <div class="col-span-full py-40 quantum-glass rounded-[48px] border border-slate-100 flex flex-col items-center gap-8 group">
                    <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 group-hover:scale-110 group-hover:rotate-12 transition-all">
                        <i class="fas fa-money-bill-transfer text-6xl"></i>
                    </div>
                    <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.5em]">Zero capital flow detected</p>
                </div>`;
                return;
            }

            document.getElementById('tx-list').innerHTML = txs.map((t, idx) => {
                const isCredit = t.type === 'credit';
                
                return `
                <div class="quantum-card p-8 group hover:border-rose-500/30 transition-all flex flex-col gap-8 relative overflow-hidden" style="animation-delay: ${idx * 0.05}s">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-rose-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-[20px] ${isCredit ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'} flex items-center justify-center text-xl shadow-inner border border-white transition-transform group-hover:scale-110">
                                <i class="fas ${isCredit ? 'fa-arrow-down-left' : 'fa-arrow-up-right'}"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-black text-slate-900 text-[16px] tracking-tight truncate">${t.user_name || 'Protocol Hub'}</h4>
                                <span class="bg-slate-50 text-slate-400 px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest border border-slate-100">${t.reference}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 relative z-10 flex items-end justify-between">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Quantum Flow</span>
                            <p class="text-3xl font-black ${isCredit ? 'text-emerald-500' : 'text-slate-900'} tracking-tighter">${isCredit ? '+' : '-'}₦${parseFloat(t.amount).toLocaleString()}</p>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">${t.status}</span>
                    </div>
                </div>
                `;
            }).join('');
        }
    }

    window.downloadLedger = () => showToast('Financial export transmitted to secure vault', 'success');
    window.filterFlux = () => showToast('Flux filters active', 'success');

    loadGlobalFlux();
</script>
