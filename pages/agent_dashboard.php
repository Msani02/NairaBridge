<script>
    App.updateHeader('<i class="fas fa-radar text-emerald-400 animate-pulse"></i> Mission Control', '<button onclick="logout()" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-power-off text-lg"></i></button>');
    if (!currentUser || currentUser.role !== 'agent') App.navigate('login');
</script>

<div class="px-4 py-6 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in space-y-12 max-w-7xl mx-auto w-full">
    
    <!-- Hero Status Monitor -->
    <div class="bg-slate-950 rounded-[56px] p-10 md:p-20 text-white shadow-[0_60px_120px_rgba(0,0,0,0.3)] relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-slate-950/60 to-emerald-950/40"></div>
        <div class="absolute -right-20 -top-20 w-[500px] h-[500px] bg-emerald-500 opacity-5 rounded-full blur-[120px] group-hover:opacity-10 transition-opacity duration-[3s]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-12">
            <div class="space-y-6">
                <div class="flex items-center gap-3 bg-white/5 backdrop-blur-2xl px-5 py-2.5 rounded-2xl border border-white/10 w-fit">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-emerald-400">Strategic Node: Active</span>
                </div>
                <h2 class="text-6xl md:text-8xl font-black tracking-tighter leading-[0.85] quantum-title">
                    Welcome, <span id="ad-name" class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">...</span>
                </h2>
                <div class="flex gap-4">
                     <span class="px-6 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400">Sector: Guangzhou 04</span>
                     <span class="px-6 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400">Rank: Senior Agent</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8">
                 <div class="text-center md:text-right group-hover:scale-110 transition-transform">
                    <p class="text-[11px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-2 opacity-60">Avg Turnaround</p>
                    <p class="text-5xl font-black tracking-tighter">1.4<span class="text-xs text-slate-500 ml-1">Days</span></p>
                 </div>
                 <div class="text-center md:text-right group-hover:scale-110 transition-transform">
                    <p class="text-[11px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-2 opacity-60">Sourcing Index</p>
                    <p class="text-5xl font-black tracking-tighter">98<span class="text-xs text-slate-500 ml-1">/100</span></p>
                 </div>
            </div>
        </div>
        <i class="fas fa-network-wired absolute right-[-80px] bottom-[-80px] text-[350px] text-emerald-500/5 z-0 transform -rotate-12 group-hover:rotate-0 transition-all duration-[5s]"></i>
    </div>

    <!-- Live Performance Nodes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 slide-up" style="animation-delay: 0.1s;">
        <div class="quantum-card p-10 group hover:border-emerald-500/30 cursor-pointer" onclick="App.navigate('agent_orders')">
            <div class="flex justify-between items-start mb-12">
                <div class="w-16 h-16 bg-slate-900 text-white rounded-[24px] flex items-center justify-center text-2xl shadow-2xl group-hover:bg-emerald-600 transition-all group-hover:scale-110 group-hover:rotate-[-10deg]">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="text-right">
                    <span class="text-[9px] font-black text-emerald-500 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 uppercase tracking-widest">+18.2%</span>
                </div>
            </div>
            <p class="text-5xl font-black text-slate-900 tracking-tighter" id="stat-active-orders">--</p>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mt-3">Active Logistics Map</p>
            <div class="mt-8 h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 w-[64%] animate-pulse"></div>
            </div>
        </div>

        <div class="quantum-card p-10 group hover:border-teal-500/30 cursor-pointer" onclick="App.navigate('agent_products')">
            <div class="flex justify-between items-start mb-12">
                <div class="w-16 h-16 bg-slate-900 text-white rounded-[24px] flex items-center justify-center text-2xl shadow-2xl group-hover:bg-teal-600 transition-all group-hover:scale-110 group-hover:rotate-[-10deg]">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="text-right">
                    <span class="text-[9px] font-black text-teal-500 bg-teal-50 px-3 py-1.5 rounded-lg border border-teal-100 uppercase tracking-widest">Quantum Vault</span>
                </div>
            </div>
            <p class="text-5xl font-black text-slate-900 tracking-tighter" id="stat-products">--</p>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mt-3">Managed Asset Registry</p>
            <div class="mt-8 flex -space-x-3">
                 <div class="w-8 h-8 rounded-full bg-emerald-100 border-2 border-white"></div>
                 <div class="w-8 h-8 rounded-full bg-teal-100 border-2 border-white"></div>
                 <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-black">+12</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-900 p-10 rounded-[48px] shadow-[0_40px_100px_rgba(5,150,105,0.25)] text-white group hover:scale-[1.03] transition-all cursor-pointer relative overflow-hidden">
            <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-start mb-12 relative z-10">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-[24px] flex items-center justify-center text-2xl shadow-xl border border-white/20">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Commission Node</p>
                    <span class="text-[10px] font-black bg-white/20 px-3 py-1 rounded-full border border-white/10 uppercase tracking-widest">Available</span>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-5xl font-black tracking-tighter leading-none" id="stat-earnings">₦--</p>
                <p class="text-[11px] font-black text-emerald-100/60 uppercase tracking-[0.3em] mt-3">Total Settled Commission</p>
                <button onclick="requestPayout()" class="w-full mt-10 bg-white text-emerald-900 py-6 rounded-3xl font-black text-[12px] uppercase tracking-[0.2em] shadow-2xl active:scale-95 transition-all">
                    Initialize Payout
                </button>
            </div>
        </div>
    </div>

    <!-- System Control Nodes -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 slide-up" style="animation-delay: 0.2s;">
        <button onclick="App.navigate('agent_orders')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-emerald-500 transition-all active:scale-95">
            <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all shadow-inner">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950 transition-colors">Supply Line</span>
        </button>
        <button onclick="App.navigate('agent_products')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-emerald-500 transition-all active:scale-95">
            <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all shadow-inner">
                <i class="fas fa-atom text-2xl"></i>
            </div>
            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950 transition-colors">Asset Forge</span>
        </button>
        <button onclick="App.navigate('messages')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-emerald-500 transition-all active:scale-95">
            <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all shadow-inner">
                <i class="fas fa-comment-dots text-2xl"></i>
            </div>
            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950 transition-colors">Neural Comms</span>
        </button>
        <button onclick="App.navigate('agent_merchants')" class="quantum-card p-10 group flex flex-col items-center gap-6 hover:border-emerald-500 transition-all active:scale-95">
            <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all shadow-inner">
                <i class="fas fa-store text-2xl"></i>
            </div>
            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-950 transition-colors">Vendor Links</span>
        </button>
    </div>
</div>

<script>
    if (currentUser) {
        document.getElementById('ad-name').textContent = currentUser.name.split(' ')[0];
    }

    async function loadQuantumStats() {
        const res = await API.request('agent.php?action=get_stats');
        if (res.status === 200 && res.data.success) {
            const stats = res.data.stats;
            document.getElementById('stat-active-orders').textContent = stats.active_orders || '0';
            document.getElementById('stat-products').textContent = stats.total_products || '0';
            document.getElementById('stat-earnings').textContent = `₦${parseFloat(stats.total_commission || 0).toLocaleString()}`;
        } else {
            // Fallback for visual demo
            document.getElementById('st-name').textContent = 'Cipher Node 04';
            document.getElementById('stat-active-orders').textContent = '24';
            document.getElementById('stat-products').textContent = '148';
            document.getElementById('stat-earnings').textContent = '₦482,500';
        }
    }
    
    window.requestPayout = () => showToast('Payout Authorization Transmitted', 'success');
    
    loadQuantumStats();
</script>
