<script>
    App.updateHeader('<a href="#home" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></a>', '<div class="flex items-center gap-3"><button onclick="toggleWishlistInProduct()" id="wish-btn" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-heart text-lg"></i></button></div>', false);
</script>

<div id="product-studio" class="px-4 py-6 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-start">
        
        <!-- Studio Asset Canvas -->
        <div class="w-full lg:w-[60%] sticky top-0 slide-up">
            <div class="relative rounded-[56px] overflow-hidden bg-white shadow-[0_60px_120px_-20px_rgba(0,0,0,0.08)] group/studio border border-slate-50">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none"></div>
                <div id="p-img-container" class="aspect-square w-full flex items-center justify-center bg-transparent relative z-10">
                    <i class="fas fa-circle-notch fa-spin text-emerald-500 text-4xl opacity-20"></i>
                </div>
                
                <!-- Floating Price Node -->
                <div class="absolute bottom-10 right-10 z-20 slide-up" style="animation-delay: 0.3s;">
                    <div class="quantum-glass p-8 rounded-[40px] shadow-2xl border border-white/40 flex flex-col items-end backdrop-blur-3xl group-hover/studio:scale-110 transition-transform duration-700">
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-2 opacity-60">Verified Valuation</span>
                        <div class="flex items-baseline gap-2">
                             <span class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter" id="p-price">--</span>
                             <span class="text-xs font-black text-slate-300">NGN</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Asset Gallery Nodes -->
            <div id="p-gallery" class="flex gap-4 mt-8 px-4 overflow-x-auto no-scrollbar slide-up" style="animation-delay: 0.4s;">
                <!-- Dynamically filled -->
            </div>
        </div>

        <!-- Quantum Intelligence Panel -->
        <div class="w-full lg:w-[40%] space-y-12 slide-up" style="animation-delay: 0.2s;">
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <span class="px-5 py-2 bg-emerald-950 text-emerald-400 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border border-emerald-500/20 shadow-xl">Quantum Verified</span>
                    <span id="p-category" class="px-5 py-2 bg-white/50 backdrop-blur-md rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border border-slate-100 shadow-sm text-slate-400">Category</span>
                </div>
                <h1 id="p-name" class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-[0.9] quantum-title">Loading Asset...</h1>
                <p id="p-desc" class="text-slate-400 text-lg font-medium leading-relaxed opacity-80">Syncing with global sourcing nodes to retrieve asset intelligence metadata...</p>
            </div>

            <div class="space-y-6 pt-10 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Sourcing node</h4>
                    <div class="flex items-center gap-2 group/merchant cursor-pointer">
                        <span id="p-merchant" class="text-[12px] font-black text-slate-900 tracking-tight">Verified Merchant</span>
                        <i class="fas fa-chevron-right text-[10px] text-slate-300 group-hover/merchant:text-emerald-500 transition-colors"></i>
                    </div>
                </div>
                
                <!-- Action Hub -->
                <div class="grid grid-cols-1 gap-4 pt-6">
                    <button onclick="addToQuantumCart()" class="neon-button w-full flex items-center justify-center gap-4 py-7 group/cart">
                        <i class="fas fa-shuttle-space text-lg transition-transform group-hover/cart:rotate-[-45deg]"></i>
                        <span>Transmit to Shipment</span>
                    </button>
                    <button onclick="openQuantumComms()" class="w-full bg-white border border-slate-100 py-6 rounded-3xl font-black text-[11px] uppercase tracking-[0.3em] text-slate-400 hover:text-emerald-500 hover:border-emerald-200 transition-all flex items-center justify-center gap-3 shadow-xl hover:-translate-y-1 active:scale-95 group/comms">
                        <i class="fas fa-comment-nodes animate-pulse group-hover/comms:scale-125 transition-transform"></i> Link Comms Terminal
                    </button>
                </div>
            </div>

            <!-- Quantum Review Nodes -->
            <div class="space-y-8 pt-10">
                <div class="flex items-center justify-between">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Client Feedback</h4>
                    <button onclick="openReviewModal()" class="text-[10px] font-black text-emerald-500 uppercase tracking-widest hover:underline">+ Authorize Review</button>
                </div>
                <div id="p-reviews" class="space-y-6">
                    <!-- Loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const params = App.getParams();
    let currentProduct = null;

    async function loadQuantumAsset() {
        const id = params.id;
        if (!id) return App.navigate('home');

        const res = await API.products.get(id);
        if (res.status === 200 && res.data.success) {
            currentProduct = res.data.product;
            const p = currentProduct;
            
            document.getElementById('p-name').textContent = p.name;
            document.getElementById('p-price').textContent = parseFloat(p.price_naira).toLocaleString();
            document.getElementById('p-desc').textContent = p.description || 'Global asset intelligence report pending.';
            document.getElementById('p-category').textContent = p.category || 'General Cargo';
            document.getElementById('p-merchant').textContent = p.merchant_name || 'Protocol Sourcing';
            
            document.getElementById('p-img-container').innerHTML = `<img src="${p.image_url}" class="w-full h-full object-contain p-10 hover:scale-105 transition-transform duration-[2s]">`;
            
            // Wishlist state check
            if (p.is_wishlisted) document.getElementById('wish-btn').classList.add('text-rose-500', 'bg-rose-50');
            
            loadReviews(p.id);
        }
    }

    async function loadReviews(id) {
        const res = await API.products.getReviews(id);
        if (res.status === 200 && res.data.success) {
            const revs = res.data.reviews;
            if (revs.length === 0) {
                document.getElementById('p-reviews').innerHTML = `<p class="text-[12px] font-medium text-slate-300 italic">No feedback entries detected in the quantum registry.</p>`;
                return;
            }
            document.getElementById('p-reviews').innerHTML = revs.map(r => `
                <div class="quantum-glass p-6 rounded-[28px] border border-slate-50 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xs shadow-lg">
                                ${r.user_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h5 class="font-black text-slate-900 text-[13px] tracking-tight">${r.user_name}</h5>
                                <div class="flex gap-0.5 mt-1">
                                    ${Array(5).fill().map((_, i) => `<i class="fas fa-star text-[8px] ${i < r.rating ? 'text-amber-400' : 'text-slate-100'}"></i>`).join('')}
                                </div>
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Quantum Verified</span>
                    </div>
                    <p class="text-[13px] font-medium text-slate-500 leading-relaxed relative z-10">${r.review_text}</p>
                </div>
            `).join('');
        }
    }

    window.addToQuantumCart = () => {
        if (!currentProduct) return;
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const idx = cart.findIndex(i => i.id === currentProduct.id);
        if (idx > -1) cart[idx].quantity += 1;
        else cart.push({ ...currentProduct, quantity: 1 });
        localStorage.setItem('cart', JSON.stringify(cart));
        App.updateCartBadge('cart-badge');
        showToast('Asset Synchronized to Cargo', 'success');
    };

    window.toggleWishlistInProduct = async () => {
        if (!currentProduct) return;
        const res = await API.products.toggleWishlist(currentProduct.id);
        if (res.status === 200 && res.data.success) {
            const btn = document.getElementById('wish-btn');
            if (res.data.action === 'added') {
                btn.classList.add('text-rose-500', 'bg-rose-50');
                showToast('Target Logged to Wishlist', 'success');
            } else {
                btn.classList.remove('text-rose-500', 'bg-rose-50');
                showToast('Target Removed', 'error');
            }
        }
    };

    window.openQuantumComms = () => {
        if (currentProduct) {
            App.navigate(`messages?contactId=${currentProduct.agent_id || 1}&contactName=${encodeURIComponent(currentProduct.merchant_name || 'Protocol Sourcing')}&orderId=0`);
        }
    };

    loadQuantumAsset();
</script>
