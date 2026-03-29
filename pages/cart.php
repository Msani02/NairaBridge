<script>
    App.updateHeader('<i class="fas fa-shopping-cart text-emerald-500"></i> Cargo Manifest', '<button onclick="App.navigate(\'home\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-atom"></i></button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-40 md:pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">
    
    <!-- Hero Header -->
    <div class="px-4">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_#10b981]"></span>
            <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Shipment Protocol</h3>
        </div>
        <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Cargo <br><span class="text-emerald-500">Manifest</span></h2>
    </div>

    <!-- Cart Content -->
    <div class="flex flex-col lg:flex-row gap-10 items-start px-4">
        
        <!-- Items List -->
        <div class="flex-1 w-full">
            
            <div id="c-empty" class="hidden flex-col items-center justify-center py-40 quantum-glass rounded-[48px] border border-slate-100 text-center gap-8 group">
                <div class="w-32 h-32 bg-slate-50 rounded-[40px] flex items-center justify-center text-slate-200 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-inner">
                    <i class="fas fa-box-open text-6xl"></i>
                </div>
                <div class="space-y-3">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Empty Manifest</h3>
                    <p class="text-slate-400 font-medium text-lg">No assets loaded into the cargo hold.</p>
                </div>
                <button onclick="App.navigate('home')" class="neon-button px-16 py-6">
                    <i class="fas fa-satellite-dish mr-3"></i> Browse Quantum Registry
                </button>
            </div>

            <div id="c-items" class="space-y-6 hidden"></div>
        </div>

        <!-- Order Summary Panel -->
        <div id="c-footer" class="hidden lg:w-[380px] xl:w-[420px] flex-shrink-0">
            
            <!-- Desktop Summary -->
            <div class="hidden lg:block quantum-card p-10 sticky top-28">
                <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400 mb-8">Protocol Summary</h3>
                <div class="space-y-5 mb-10">
                    <div class="flex justify-between items-center text-[13px] font-bold text-slate-400">
                        <span>Asset Units</span>
                        <span id="c-units-d" class="font-black text-slate-900">0</span>
                    </div>
                    <div class="flex justify-between items-center text-[13px] font-bold text-slate-400">
                        <span>Logistics Fee</span>
                        <span class="font-black text-emerald-500">FREE</span>
                    </div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex justify-between items-end">
                        <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Total Valuation</span>
                        <span id="c-total-d" class="text-4xl font-black text-slate-900 tracking-tighter">₦0</span>
                    </div>
                </div>
                <button id="btn-checkout-d" onclick="checkout('d')" class="neon-button w-full py-6">
                    <i class="fas fa-shuttle-space mr-3 text-lg"></i> Transmit Manifest
                </button>
            </div>

            <!-- Mobile Fixed Bottom -->
            <div class="lg:hidden fixed bottom-0 left-0 w-full z-40 p-4 pb-[max(1rem,env(safe-area-inset-bottom))] quantum-glass border-t border-white/50 backdrop-blur-3xl">
                <div class="flex justify-between items-center mb-4 max-w-lg mx-auto">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Total Valuation</span>
                    <span id="c-total-m" class="text-3xl font-black text-slate-900 tracking-tighter">₦0</span>
                </div>
                <button id="btn-checkout-m" onclick="checkout('m')" class="neon-button w-full py-5 max-w-lg mx-auto block">
                    <i class="fas fa-shuttle-space mr-2"></i> Transmit Manifest
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');

    function renderCart() {
        cart = JSON.parse(localStorage.getItem('cart') || '[]');

        if (cart.length === 0) {
            document.getElementById('c-empty').classList.remove('hidden');
            document.getElementById('c-empty').classList.add('flex');
            document.getElementById('c-items').classList.add('hidden');
            document.getElementById('c-footer').classList.add('hidden');
            return;
        }

        document.getElementById('c-empty').classList.add('hidden');
        document.getElementById('c-items').classList.remove('hidden');
        document.getElementById('c-footer').classList.remove('hidden');

        let total = 0;
        document.getElementById('c-items').innerHTML = cart.map((item, idx) => {
            const itemTotal = (parseFloat(item.price_naira || item.price) * item.quantity);
            total += itemTotal;
            return `
            <div class="quantum-card p-8 group hover:border-emerald-500/20 flex items-center gap-8 relative overflow-hidden" style="animation-delay:${idx*0.05}s">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-24 h-24 md:w-32 md:h-32 bg-slate-50 rounded-[28px] flex-shrink-0 overflow-hidden border border-slate-100 group-hover:scale-105 transition-transform duration-700 shadow-inner">
                    ${item.image_url ? `<img src="${item.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center text-slate-100"><i class="fas fa-cube text-4xl"></i></div>`}
                </div>

                <div class="flex-1 min-w-0 space-y-4">
                    <h4 class="text-[18px] font-black text-slate-950 tracking-tight truncate group-hover:text-emerald-600 transition-colors">${item.name}</h4>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-slate-50 rounded-[18px] p-1.5 border border-slate-100 shadow-inner">
                            <button onclick="updateQty(${idx}, -1)" class="w-9 h-9 rounded-[14px] flex items-center justify-center text-slate-400 hover:bg-white hover:text-slate-900 transition-all font-black text-lg active:scale-90 shadow-sm">−</button>
                            <span class="w-10 text-center font-black text-slate-900">${item.quantity}</span>
                            <button onclick="updateQty(${idx}, 1)" class="w-9 h-9 rounded-[14px] flex items-center justify-center text-slate-400 hover:bg-white hover:text-slate-900 transition-all font-black text-lg active:scale-90 shadow-sm">+</button>
                        </div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">× ₦${parseFloat(item.price_naira || item.price).toLocaleString()}</span>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-4">
                    <button onclick="removeItem(${idx})" class="w-10 h-10 quantum-glass rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 transition-all active:scale-90">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                    <span class="text-xl font-black text-slate-900 tracking-tighter">₦${itemTotal.toLocaleString()}</span>
                </div>
            </div>
            `;
        }).join('');

        const fmt = `₦${total.toLocaleString()}`;
        const units = cart.reduce((s, i) => s + i.quantity, 0);
        document.getElementById('c-total-m').textContent = fmt;
        document.getElementById('c-total-d').textContent = fmt;
        if (document.getElementById('c-units-d')) document.getElementById('c-units-d').textContent = units;
    }

    window.updateQty = (idx, delta) => {
        cart[idx].quantity += delta;
        if (cart[idx].quantity <= 0) cart.splice(idx, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    };

    window.removeItem = (idx) => {
        cart.splice(idx, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    };

    window.checkout = async (src) => {
        if (!currentUser) { showToast('Identity verification required', 'error'); App.navigate('login'); return; }
        if (currentUser.role !== 'user') { showToast('Procurement restricted to Buyer nodes', 'error'); return; }

        const btn = document.getElementById(`btn-checkout-${src}`);
        const prev = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-atom fa-spin"></i> Securing Pipeline...';
        btn.disabled = true;

        const res = await API.orders.place(cart);
        if (res.status === 200 && res.data.success) {
            localStorage.removeItem('cart');
            showToast('Manifest Transmitted!', 'success');
            setTimeout(() => App.navigate('user_orders'), 1500);
        } else {
            showToast(res.data.message || 'Checkout protocol failed', 'error');
            btn.innerHTML = prev;
            btn.disabled = false;
        }
    };

    renderCart();
</script>
