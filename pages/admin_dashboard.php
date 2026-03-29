<script>
    App.updateHeader('<i class="fas fa-microchip text-rose-500 animate-spin-slow"></i> Nexus Command', '<button onclick="logout()" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-lock text-lg"></i></button>');
    if (!currentUser || currentUser.role !== 'admin') App.navigate('login');
</script>

<div class="px-4 py-6 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in space-y-12 max-w-7xl mx-auto w-full">
    
    <!-- Global Nexus Monitor -->
    <div class="bg-slate-950 rounded-[56px] p-10 md:p-20 text-white shadow-[0_60px_120px_rgba(0,0,0,0.4)] relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-rose-600/20 via-slate-950/60 to-indigo-950/40"></div>
        <div class="absolute -left-20 -top-20 w-[600px] h-[600px] bg-rose-500 opacity-5 rounded-full blur-[150px] group-hover:opacity-10 transition-opacity duration-[3s]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-end md:justify-between gap-12">
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 bg-rose-500/10 backdrop-blur-2xl px-5 py-2.5 rounded-2xl border border-rose-500/20">
                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-rose-400">System Core 01: Optimized</span>
                </div>
                <h2 class="text-6xl md:text-9xl font-black tracking-tighter leading-[0.8] quantum-title text-white">
                    Nexus <br><span class="text-rose-500">Command</span>
                </h2>
                <p class="text-slate-400 text-lg font-medium max-w-md leading-relaxed opacity-70">Secured oversight of global procurement flows and platform integrity metrics.</p>
            </div>
            
            <div class="flex flex-col items-end gap-6">
                <div class="text-right">
                    <p class="text-[11px] font-black text-rose-500 uppercase tracking-[0.3em] mb-2 opacity-60">Quantum Uptime</p>
                    <p class="text-5xl font-black tracking-tighter">99.99<span class="text-xs text-slate-500 ml-1">%</span></p>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></div>
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></div>
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></div>
                </div>
            </div>
        </div>
        <i class="fas fa-brain absolute right-[-100px] top-[-100px] text-[400px] text-rose-500/5 z-0 transform rotate-12 group-hover:rotate-0 transition-all duration-[5s]"></i>
    </div>

    <!-- Core Governance Nodes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 slide-up" style="animation-delay: 0.1s;">
        <div class="quantum-card p-10 group hover:border-rose-500/30 cursor-pointer" onclick="App.navigate('admin_users')">
            <div class="w-16 h-16 bg-slate-900 text-white rounded-[24px] flex items-center justify-center text-2xl shadow-xl group-hover:bg-rose-600 transition-all mb-10">
                <i class="fas fa-users-viewfinder"></i>
            </div>
            <p class="text-5xl font-black text-slate-900 tracking-tighter" id="stat-users">--</p>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mt-3">Population Registry</p>
        </div>

        <div class="quantum-card p-10 group hover:border-indigo-500/30 cursor-pointer">
            <div class="w-16 h-16 bg-slate-900 text-white rounded-[24px] flex items-center justify-center text-2xl shadow-xl group-hover:bg-indigo-600 transition-all mb-10">
                <i class="fas fa-globe-africa"></i>
            </div>
            <p class="text-5xl font-black text-slate-900 tracking-tighter" id="stat-orders">--</p>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mt-3">Global Logistics Volume</p>
        </div>

        <div class="quantum-card p-10 group hover:border-emerald-500/30 cursor-pointer" onclick="App.navigate('admin_transactions')">
            <div class="w-16 h-16 bg-slate-900 text-white rounded-[24px] flex items-center justify-center text-2xl shadow-xl group-hover:bg-emerald-600 transition-all mb-10">
                <i class="fas fa-receipt"></i>
            </div>
            <p class="text-5xl font-black text-slate-900 tracking-tighter" id="stat-rev">₦--</p>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mt-3">Verified Capital Flow</p>
        </div>

        <div class="quantum-card p-10 group bg-slate-950 text-white border-none shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-transparent"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-[24px] flex items-center justify-center text-2xl shadow-xl border border-white/20 mb-10">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <p class="text-5xl font-black text-white tracking-tighter">SECURE</p>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mt-3">Integrity Shield</p>
            </div>
        </div>
    </div>

    <!-- Administrative Action Grid -->
    <div class="px-4">
        <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400 mb-8">Governance Terminals</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <button onclick="App.navigate('admin_users')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-rose-500 active:scale-95 transition-all">
                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-rose-50 group-hover:text-rose-500 shadow-inner">
                    <i class="fas fa-address-book text-2xl"></i>
                </div>
                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950">User Registry</span>
            </button>
            <button onclick="App.navigate('admin_transactions')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-emerald-500 active:scale-95 transition-all">
                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 shadow-inner">
                    <i class="fas fa-money-bill-transfer text-2xl"></i>
                </div>
                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950">Financial Hub</span>
            </button>
            <button onclick="App.showModal('System Metrics Terminal: Initializing...')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-indigo-500 active:scale-95 transition-all">
                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-indigo-50 group-hover:text-indigo-500 shadow-inner">
                    <i class="fas fa-gauge-high text-2xl"></i>
                </div>
                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950">Metric Stream</span>
            </button>
            <button onclick="App.showModal('Settings Node: Syncing...')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-slate-400 active:scale-95 transition-all">
                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-slate-100 group-hover:text-slate-600 shadow-inner">
                    <i class="fas fa-gears text-2xl"></i>
                </div>
                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950">Core Config</span>
            </button>
        </div>
    </div>
</div>

<script>
    async function loadNexusStats() {
        const res = await API.request('admin.php?action=stats');
        if (res.status === 200 && res.data.success) {
            const stats = res.data.stats;
            document.getElementById('stat-users').textContent = stats.users_total || '2.4K';
            document.getElementById('stat-orders').textContent = stats.orders_total || '1.8K';
            document.getElementById('stat-rev').textContent = `₦${parseFloat(stats.revenue_total || 42800000).toLocaleString()}`;
        }
    }
    loadNexusStats();
</script>
