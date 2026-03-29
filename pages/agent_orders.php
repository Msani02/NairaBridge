<script>
    App.updateHeader('<a href="#agent_dashboard" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></a>', '<button onclick="openContainerModal()" class="neon-button px-8 py-4 text-[11px]"><i class="fas fa-plus mr-2"></i> New Container</button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">

    <!-- Mission Hero -->
    <div class="px-4">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse shadow-[0_0_10px_#6366f1]"></span>
            <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Supply Chain Intelligence</h3>
        </div>
        <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Supply <br><span class="text-indigo-500">Line</span></h2>
    </div>

    <!-- Status Nodes Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 px-4">
        <div class="quantum-glass p-8 rounded-[36px] border border-indigo-100/50 bg-indigo-50/20 flex flex-col gap-2">
            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">In Transit</span>
            <span class="text-3xl font-black text-slate-900" id="stat-in-transit">--</span>
        </div>
        <div class="quantum-glass p-8 rounded-[36px] border border-emerald-100/50 bg-emerald-50/20 flex flex-col gap-2">
            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Delivered</span>
            <span class="text-3xl font-black text-slate-900" id="stat-delivered">--</span>
        </div>
        <div class="quantum-glass p-8 rounded-[36px] border border-amber-100/50 bg-amber-50/20 flex flex-col gap-2">
            <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Pending</span>
            <span class="text-3xl font-black text-slate-900" id="stat-pending">--</span>
        </div>
        <div class="quantum-glass p-8 rounded-[36px] border border-slate-100 flex flex-col gap-2">
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Total</span>
            <span class="text-3xl font-black text-slate-900" id="stat-total">--</span>
        </div>
    </div>

    <!-- Orders Feed -->
    <div id="ao-list" class="space-y-6 px-4 slide-up" style="animation-delay: 0.1s;">
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
    async function loadSupplyLine() {
        const res = await API.request('agent.php?action=orders');
        if (res.status === 200 && res.data.success) {
            const orders = res.data.orders;

            document.getElementById('stat-total').textContent = orders.length;
            document.getElementById('stat-in-transit').textContent = orders.filter(o => o.status === 'processing' || o.status === 'shipped').length;
            document.getElementById('stat-delivered').textContent = orders.filter(o => o.status === 'delivered').length;
            document.getElementById('stat-pending').textContent = orders.filter(o => o.status === 'pending').length;

            if (orders.length === 0) {
                document.getElementById('ao-list').innerHTML = `
                <div class="py-40 quantum-glass rounded-[48px] border border-slate-100 flex flex-col items-center gap-8 group">
                    <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-inner">
                        <i class="fas fa-ship text-6xl"></i>
                    </div>
                    <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.5em]">Supply chain is clear - no orders detected</p>
                </div>`;
                return;
            }

            document.getElementById('ao-list').innerHTML = orders.map((o, idx) => {
                const statusMap = {
                    pending: 'text-amber-500 bg-amber-50 border-amber-100',
                    processing: 'text-indigo-500 bg-indigo-50 border-indigo-100',
                    shipped: 'text-teal-500 bg-teal-50 border-teal-100',
                    delivered: 'text-emerald-500 bg-emerald-50 border-emerald-100',
                    cancelled: 'text-rose-500 bg-rose-50 border-rose-100'
                };
                const cls = statusMap[o.status] || 'text-slate-500 bg-slate-50 border-slate-100';

                return `
                <div class="quantum-card p-8 md:p-10 group hover:border-indigo-500/20 flex flex-col md:flex-row md:items-center gap-8 relative overflow-hidden" style="animation-delay: ${idx * 0.05}s">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>

                    <div class="w-20 h-20 rounded-[28px] bg-slate-50 flex items-center justify-center text-slate-200 flex-shrink-0 group-hover:scale-110 transition-transform shadow-inner border border-slate-100">
                        <i class="fas fa-ship text-3xl"></i>
                    </div>

                    <div class="flex-1 space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="${cls} px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border shadow-sm">${o.status}</span>
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] px-4 py-1.5 border border-slate-50 rounded-xl">ORD-${o.id}</span>
                        </div>
                        <h4 class="text-2xl font-black text-slate-950 tracking-tight group-hover:text-indigo-600 transition-colors">${o.product_name || 'Quantum Cargo'}</h4>
                        <div class="flex flex-wrap gap-6 text-[12px] font-medium text-slate-400">
                            <span class="flex items-center gap-2"><i class="fas fa-user text-indigo-400"></i> ${o.buyer_name || 'Buyer Node'}</span>
                            <span class="flex items-center gap-2"><i class="fas fa-calendar text-slate-200"></i> ${new Date(o.created_at).toLocaleDateString()}</span>
                            <span class="flex items-center gap-2 font-black text-slate-900"><i class="fas fa-tag text-slate-200"></i> ₦${parseFloat(o.total_price || 0).toLocaleString()}</span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <select onchange="updateOrderStatus(${o.id}, this.value)" class="bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3 text-[11px] font-black text-slate-700 outline-none cursor-pointer hover:border-indigo-300 transition-colors">
                            <option ${o.status === 'pending' ? 'selected' : ''} value="pending">Pending</option>
                            <option ${o.status === 'processing' ? 'selected' : ''} value="processing">Processing</option>
                            <option ${o.status === 'shipped' ? 'selected' : ''} value="shipped">Shipped</option>
                            <option ${o.status === 'delivered' ? 'selected' : ''} value="delivered">Delivered</option>
                            <option ${o.status === 'cancelled' ? 'selected' : ''} value="cancelled">Cancelled</option>
                        </select>
                        <button onclick="App.navigate('messages?contactId=${o.user_id}&orderId=${o.id}')" class="w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-indigo-500 transition-all active:scale-95 shadow-md">
                            <i class="fas fa-comment-dots"></i>
                        </button>
                    </div>
                </div>`;
            }).join('');
        }
    }

    window.updateOrderStatus = async (id, status) => {
        const res = await API.request('agent.php?action=update_order', 'POST', { order_id: id, status });
        if (res.status === 200 && res.data.success) {
            showToast(`Order ORD-${id} status synchronized`, 'success');
            loadSupplyLine();
        } else {
            showToast('Status update failed', 'error');
        }
    };

    window.openContainerModal = () => showToast('Container management launching soon...', 'success');

    loadSupplyLine();
</script>
