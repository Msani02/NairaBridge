<script>
    App.updateHeader('<i class="fas fa-vault text-teal-400"></i> Capital Hub', '<button onclick="App.navigate(\'home\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-teal-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-add"></i></button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">
    
    <!-- Quantum Capital Vault -->
    <div class="bg-slate-950 rounded-[56px] p-12 md:p-24 text-white shadow-[0_60px_120px_rgba(0,0,0,0.4)] relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600/20 via-slate-950/60 to-emerald-950/40"></div>
        <div class="absolute -right-20 -top-20 w-[600px] h-[600px] bg-teal-500 opacity-5 rounded-full blur-[150px] group-hover:opacity-10 transition-opacity duration-[3s]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-12">
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 bg-teal-500/10 backdrop-blur-2xl px-5 py-2.5 rounded-2xl border border-teal-500/20">
                    <span class="w-1.5 h-1.5 bg-teal-400 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-teal-400">Quantum Reserve: Synchronized</span>
                </div>
                <div class="space-y-2">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em]">Available Capital</p>
                    <h2 class="text-7xl md:text-9xl font-black tracking-tighter leading-none" id="wal-balance">₦0.00</h2>
                </div>
            </div>
            
            <div class="flex flex-col gap-4">
                 <button onclick="requestQuantumDeposit()" class="neon-button px-12 py-7 text-[13px] tracking-[0.2em] shadow-[0_20px_50px_rgba(16,185,129,0.25)]">
                    <i class="fas fa-plus-circle mr-3"></i> Top-up Reserve
                 </button>
                 <button onclick="requestQuantumWithdraw()" class="w-full bg-white/5 border border-white/10 py-6 rounded-3xl font-black text-[11px] uppercase tracking-[0.3em] text-slate-300 hover:text-white hover:border-white/20 transition-all flex items-center justify-center gap-3 backdrop-blur-xl">
                    <i class="fas fa-arrow-up-right-from-square text-xs opacity-50"></i> Transfer Asset
                 </button>
            </div>
        </div>
        <i class="fas fa-fingerprint absolute left-[-80px] bottom-[-80px] text-[350px] text-teal-500/5 z-0 transform rotate-12 group-hover:rotate-0 transition-all duration-[5s]"></i>
    </div>

    <!-- Transaction Stream -->
    <div class="px-4 space-y-8">
        <div class="flex items-center justify-between">
             <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Verified Flow Engine</h3>
             <span class="text-[9px] font-black bg-slate-100 text-slate-400 px-3 py-1.5 rounded-full uppercase tracking-widest border border-slate-200/50">Real-time Stream</span>
        </div>
        
        <div id="wal-txs" class="space-y-6 slide-up" style="animation-delay: 0.1s;">
            <!-- Loaders -->
            <div class="quantum-card p-10 animate-pulse bg-white border border-slate-50 flex items-center gap-8">
                <div class="w-20 h-20 bg-slate-50 rounded-[28px]"></div>
                <div class="flex-1 space-y-4">
                    <div class="h-5 bg-slate-50 rounded-xl w-1/3"></div>
                    <div class="h-4 bg-slate-50 rounded-xl w-1/2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadQuantumWallet() {
        const res = await API.request('wallet.php?action=balance');
        if (res.status === 200 && res.data.success) {
            document.getElementById('wal-balance').textContent = `₦${parseFloat(res.data.balance).toLocaleString()}`;
            loadTxs();
        }
    }

    async function loadTxs() {
        const res = await API.request('wallet.php?action=transactions');
        if (res.status === 200 && res.data.success) {
            const txs = res.data.transactions;
            if (txs.length === 0) {
                document.getElementById('wal-txs').innerHTML = `<div class="text-center py-20 quantum-glass rounded-[40px] border border-slate-100 italic text-slate-300 font-medium">No verified capital flows detected in your current protocol cycle.</div>`;
                return;
            }

            document.getElementById('wal-txs').innerHTML = txs.map((t, idx) => {
                const isCredit = t.type === 'credit';
                
                return `
                <div class="quantum-card p-8 group hover:border-emerald-500/30 transition-all flex flex-row items-center gap-8 relative overflow-hidden" style="animation-delay: ${idx * 0.05}s">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-16 h-16 rounded-[24px] ${isCredit ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'} flex items-center justify-center text-xl shadow-inner border border-white transition-transform group-hover:scale-110">
                        <i class="fas ${isCredit ? 'fa-arrow-down-left' : 'fa-arrow-up-right'}"></i>
                    </div>

                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between items-start">
                            <h4 class="text-[17px] font-black text-slate-950 tracking-tight leading-tight group-hover:text-emerald-600 transition-colors">${t.reference}</h4>
                            <span class="text-2xl font-black ${isCredit ? 'text-emerald-500' : 'text-slate-900'} tracking-tighter">${isCredit ? '+' : '-'}₦${parseFloat(t.amount).toLocaleString()}</span>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>${new Date(t.created_at).toLocaleDateString()}</span>
                            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                            <span>${t.status}</span>
                        </div>
                    </div>
                </div>
                `;
            }).join('');
        }
    }

    window.requestQuantumDeposit = () => showToast('Syncing payment node...', 'success');
    window.requestQuantumWithdraw = () => showToast('Transfer protocol unauthorized', 'error');

    loadQuantumWallet();
</script>
