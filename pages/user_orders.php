<script>
    App.updateHeader('<i class="fas fa-boxes-stacked text-emerald-500"></i> My Cargo', '<button onclick="App.navigate(\'home\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-plus"></i></button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">
    
    <!-- Hero Status -->
    <div class="px-4">
        <div class="flex items-center gap-3 mb-4">
             <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_#10b981]"></span>
             <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Logistics Stream</h3>
        </div>
        <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Shipment <br><span class="text-emerald-500">History</span></h2>
    </div>

    <!-- Stats Node -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 px-4">
        <div class="quantum-glass p-8 rounded-[36px] border border-slate-50 flex flex-col gap-2">
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">In Transit</span>
            <span class="text-3xl font-black text-slate-900" id="stat-transit">--</span>
        </div>
        <div class="quantum-glass p-8 rounded-[36px] border border-slate-50 flex flex-col gap-2 text-white bg-slate-950">
            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Delivered</span>
            <span class="text-3xl font-black" id="stat-delivered">--</span>
        </div>
    </div>

    <!-- Order Flux -->
    <div id="orders-list" class="space-y-8 px-4 slide-up" style="animation-delay: 0.1s;">
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

<script>
    async function loadUserCargo() {
        const res = await API.request('user.php?action=orders');
        if (res.status === 200 && res.data.success) {
            const orders = res.data.orders;
            
            // Stats
            document.getElementById('stat-transit').textContent = orders.filter(o => o.status !== 'delivered' && o.status !== 'cancelled').length;
            document.getElementById('stat-delivered').textContent = orders.filter(o => o.status === 'delivered').length;

            if (orders.length === 0) {
                document.getElementById('orders-list').innerHTML = `
                <div class="text-center py-40 quantum-glass rounded-[48px] border border-slate-100 flex flex-col items-center gap-8 group">
                    <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i class="fas fa-box-open text-6xl"></i>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.5em]">No active cargo streams</p>
                        <p class="text-slate-400 font-medium">Coordinate your first shipment via the Home terminal.</p>
                    </div>
                </div>`;
                return;
            }

            document.getElementById('orders-list').innerHTML = orders.map((o, idx) => {
                const statusColor = o.status === 'delivered' ? 'text-emerald-500 bg-emerald-50' : 
                                  o.status === 'pending' ? 'text-amber-500 bg-amber-50' : 
                                  o.status === 'cancelled' ? 'text-rose-500 bg-rose-50' : 'text-indigo-500 bg-indigo-50';
                
                return `
                <div class="quantum-card p-8 md:p-10 group hover:border-emerald-500/30 relative overflow-hidden flex flex-col md:flex-row md:items-center gap-8" style="animation-delay: ${idx * 0.1}s">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-[32px] overflow-hidden flex-shrink-0 border border-slate-50 shadow-inner group-hover:scale-105 transition-transform duration-700">
                        ${o.image_url ? `<img src="${o.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center text-slate-100"><i class="fas fa-box text-5xl"></i></div>`}
                    </div>

                    <div class="flex-1 space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="${statusColor} px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-white/50 shadow-sm">${o.status}</span>
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] px-4 py-1.5 border border-slate-50 rounded-xl">ID: NBX-${o.id}</span>
                        </div>
                        <h4 class="text-2xl font-black text-slate-950 tracking-tight leading-tight group-hover:text-emerald-600 transition-colors">${o.product_name || 'Quantum Asset'}</h4>
                        <div class="flex items-center gap-8 text-[12px] font-medium text-slate-400">
                             <span class="flex items-center gap-2"><i class="fas fa-calendar-alt text-emerald-400"></i> ${new Date(o.created_at).toLocaleDateString()}</span>
                             <span class="flex items-center gap-2 font-black text-slate-900"><i class="fas fa-tag text-slate-200"></i> ₦${parseFloat(o.total_price).toLocaleString()}</span>
                        </div>
                    </div>

                    <div class="flex gap-3 md:flex-col">
                        <button onclick="App.navigate('product?id=${o.product_id}')" class="w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all active:scale-95 shadow-md">
                            <i class="fas fa-eye text-lg"></i>
                        </button>
                        <button onclick="App.navigate('messages?orderId=${o.id}')" class="w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all active:scale-95 shadow-md">
                            <i class="fas fa-comment-dots text-lg"></i>
                        </button>
                    </div>
                </div>
                `;
            }).join('');
        }
    }
    loadUserCargo();
</script>
