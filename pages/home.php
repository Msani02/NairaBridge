<script>
    App.updateHeader('<span class="font-black text-slate-900 tracking-tighter text-xl flex items-center gap-2"><i class="fas fa-leaf text-emerald-500"></i> NairaBridge</span>', `
        <div class="flex items-center gap-3">
            <button onclick="App.navigate('login')" class="hidden md:flex items-center gap-2 quantum-glass rounded-2xl px-6 py-3 text-[11px] font-black text-slate-600 uppercase tracking-[0.2em] hover:text-emerald-500 transition-all border border-white/50 active:scale-95">
                Sign In
            </button>
            <button onclick="App.navigate('cart')" class="relative w-11 h-11 quantum-glass rounded-2xl flex items-center justify-center text-slate-500 hover:text-emerald-500 transition-all active:scale-95 border border-white/50">
                <i class="fas fa-shopping-bag"></i>
                <span id="cart-badge" class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center hidden">0</span>
            </button>
        </div>
    `, false);
    App.updateCartBadge('cart-badge');
</script>

<!-- ═══════════════════════════════════════════
     FULL PAGE LANDING LAYOUT
═══════════════════════════════════════════ -->
<div class="h-full overflow-y-auto no-scrollbar w-full" id="home-root">

    <!-- ─── HERO ──────────────────────────────── -->
    <section class="relative min-h-[88vh] flex flex-col items-center justify-center text-center px-6 py-20 overflow-hidden">
        <!-- Layered mesh gradient -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-50/60 via-white to-white"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[600px] bg-emerald-400/10 rounded-full blur-[100px]"></div>
            <div class="absolute top-40 right-0 w-[400px] h-[400px] bg-teal-400/10 rounded-full blur-[80px]"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-400/8 rounded-full blur-[100px]"></div>
        </div>

        <!-- Floating orbit rings -->
        <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] border border-emerald-500/5 rounded-full animate-[spin_40s_linear_infinite]"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-teal-500/8 rounded-full animate-[spin_25s_linear_infinite_reverse]"></div>
        </div>

        <!-- Live badge -->
        <div class="inline-flex items-center gap-2.5 bg-emerald-950 text-emerald-400 px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.4em] mb-10 shadow-[0_8px_30px_rgba(16,185,129,0.2)] border border-emerald-800 fade-in">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
            </span>
            Live sourcing from Guangzhou · Lagos
        </div>

        <!-- Giant Headline -->
        <div class="max-w-4xl mx-auto slide-up">
            <h1 class="text-[13vw] md:text-[9vw] lg:text-[7.5rem] font-black tracking-tighter leading-[0.88] text-slate-950 mb-6">
                Source
                <span class="relative inline-block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400">Smarter.</span>
                    <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 9C50 3 100 1 150 5C200 9 250 9 298 5" stroke="url(#grad)" stroke-width="3" stroke-linecap="round"/>
                        <defs><linearGradient id="grad" x1="0" y1="0" x2="300" y2="0"><stop stop-color="#10b981"/><stop offset="1" stop-color="#14b8a6"/></linearGradient></defs>
                    </svg>
                </span>
                <br>Ship Faster.
            </h1>
        </div>

        <p class="text-slate-500 text-lg md:text-xl font-medium max-w-xl mx-auto leading-relaxed mt-8 mb-12 fade-in" style="animation-delay:0.2s">
            Verified Chinese factories. Premium logistics. <br class="hidden md:block">Delivered to your door in Nigeria.
        </p>

        <!-- Search Bar -->
        <div class="w-full max-w-lg mx-auto mb-12 fade-in" style="animation-delay:0.3s">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-400 rounded-[30px] blur opacity-20 group-focus-within:opacity-60 transition-opacity duration-500"></div>
                <div class="relative bg-white rounded-[26px] border border-slate-100 shadow-[0_20px_60px_rgba(0,0,0,0.07)] flex items-center p-2">
                    <div class="w-12 h-12 flex items-center justify-center text-slate-300 ml-2">
                        <i class="fas fa-magnifying-glass text-lg"></i>
                    </div>
                    <input type="text" id="home-search" onkeydown="if(event.key==='Enter')searchProducts()"
                        class="flex-1 bg-transparent border-none outline-none focus:ring-0 text-slate-900 font-bold text-[15px] placeholder:text-slate-300 px-3"
                        placeholder="Find any product...">
                    <button onclick="searchProducts()" class="w-14 h-14 bg-slate-950 text-white rounded-[20px] flex items-center justify-center hover:bg-emerald-600 transition-all active:scale-90 shadow-xl flex-shrink-0">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Trust row -->
        <div class="flex flex-wrap justify-center gap-6 md:gap-12 text-slate-400 text-[11px] font-black uppercase tracking-[0.3em] fade-in" style="animation-delay:0.4s">
            <span class="flex items-center gap-2"><i class="fas fa-shield-check text-emerald-500"></i> Verified Factories</span>
            <span class="flex items-center gap-2"><i class="fas fa-bolt text-amber-400"></i> 7–14 Day Delivery</span>
            <span class="flex items-center gap-2"><i class="fas fa-lock text-indigo-400"></i> Secure Payments</span>
        </div>

        <!-- Scroll cue -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-300 animate-bounce">
            <span class="text-[9px] font-black uppercase tracking-[0.4em]">Browse</span>
            <i class="fas fa-chevron-down text-xs"></i>
        </div>
    </section>

    <!-- ─── CATEGORY STRIP ────────────────────── -->
    <section class="px-4 md:px-8 lg:px-16 pb-8">
        <div class="flex items-center gap-4 overflow-x-auto no-scrollbar pb-2">
            <button onclick="filterCat('')" class="cat-pill active shrink-0">✦ All</button>
            <button onclick="filterCat('electronics')" class="cat-pill shrink-0">📱 Electronics</button>
            <button onclick="filterCat('fashion')" class="cat-pill shrink-0">👗 Fashion</button>
            <button onclick="filterCat('home')" class="cat-pill shrink-0">🏠 Home & Living</button>
            <button onclick="filterCat('beauty')" class="cat-pill shrink-0">✨ Beauty</button>
            <button onclick="filterCat('sports')" class="cat-pill shrink-0">⚽ Sports</button>
            <button onclick="filterCat('luxury')" class="cat-pill shrink-0">💎 Luxury</button>
        </div>
    </section>

    <!-- ─── STATS BAR ─────────────────────────── -->
    <section class="px-4 md:px-8 lg:px-16 pb-12">
        <div class="bg-slate-950 rounded-[36px] px-8 md:px-16 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-white">
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-black tracking-tighter text-emerald-400">12K+</p>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-2">Products Live</p>
            </div>
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-black tracking-tighter text-teal-400">500+</p>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-2">Suppliers</p>
            </div>
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-black tracking-tighter text-amber-400">99%</p>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-2">On-time Rate</p>
            </div>
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-black tracking-tighter text-white">8K+</p>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mt-2">Happy Buyers</p>
            </div>
        </div>
    </section>

    <!-- ─── PRODUCT GRID ──────────────────────── -->
    <section class="px-4 md:px-8 lg:px-16 pb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2">Live Registry</p>
                <h2 class="text-3xl md:text-4xl font-black text-slate-950 tracking-tighter">Trending Now</h2>
            </div>
            <button onclick="filterCat('')" class="hidden md:flex items-center gap-2 text-[11px] font-black text-emerald-500 uppercase tracking-[0.3em] hover:underline underline-offset-4">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </button>
        </div>

        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-6">
            <!-- Skeleton loaders -->
            ${Array(8).fill(`
            <div class="rounded-[28px] overflow-hidden animate-pulse bg-white border border-slate-50">
                <div class="aspect-square bg-slate-50"></div>
                <div class="p-5 space-y-3">
                    <div class="h-4 bg-slate-50 rounded-lg w-3/4"></div>
                    <div class="h-5 bg-slate-50 rounded-lg w-1/2"></div>
                </div>
            </div>`).join('')}
        </div>
    </section>

    <!-- ─── WHY NAIRABRIDGE ───────────────────── -->
    <section class="px-4 md:px-8 lg:px-16 py-16">
        <div class="text-center mb-12">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-3">Why us</p>
            <h2 class="text-4xl md:text-5xl font-black text-slate-950 tracking-tighter">The NairaBridge Edge</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-[36px] p-10 border border-slate-50 shadow-[0_20px_60px_rgba(0,0,0,0.03)] hover:-translate-y-2 transition-all group">
                <div class="w-16 h-16 bg-emerald-50 rounded-[24px] flex items-center justify-center text-emerald-500 text-2xl mb-8 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-inner">
                    <i class="fas fa-magnifying-glass-dollar"></i>
                </div>
                <h3 class="text-xl font-black text-slate-950 tracking-tight mb-3">Factory Prices</h3>
                <p class="text-slate-400 font-medium leading-relaxed">Cut out the middlemen. Source directly from verified Chinese manufacturers at wholesale rates.</p>
            </div>
            <div class="bg-white rounded-[36px] p-10 border border-slate-50 shadow-[0_20px_60px_rgba(0,0,0,0.03)] hover:-translate-y-2 transition-all group">
                <div class="w-16 h-16 bg-teal-50 rounded-[24px] flex items-center justify-center text-teal-500 text-2xl mb-8 group-hover:scale-110 group-hover:bg-teal-500 group-hover:text-white transition-all shadow-inner">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-black text-slate-950 tracking-tight mb-3">Buyer Protection</h3>
                <p class="text-slate-400 font-medium leading-relaxed">Every order is fully insured. If it doesn't arrive or doesn't match, you get a full refund — no questions asked.</p>
            </div>
            <div class="bg-white rounded-[36px] p-10 border border-slate-50 shadow-[0_20px_60px_rgba(0,0,0,0.03)] hover:-translate-y-2 transition-all group">
                <div class="w-16 h-16 bg-amber-50 rounded-[24px] flex items-center justify-center text-amber-500 text-2xl mb-8 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-inner">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-xl font-black text-slate-950 tracking-tight mb-3">Dedicated Agent</h3>
                <p class="text-slate-400 font-medium leading-relaxed">Your personal sourcing agent in Guangzhou handles negotiation, QC, and shipping end-to-end.</p>
            </div>
        </div>
    </section>

    <!-- ─── BOTTOM CTA ────────────────────────── -->
    <section class="px-4 md:px-8 lg:px-16 pb-40">
        <div class="bg-slate-950 rounded-[48px] p-10 md:p-20 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 to-teal-600/10"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-emerald-500/10 blur-[100px]"></div>
            <div class="relative z-10">
                <h2 class="text-4xl md:text-6xl font-black tracking-tighter mb-6">Ready to source <br><span class="text-emerald-400">smarter?</span></h2>
                <p class="text-slate-400 max-w-md mx-auto mb-10 font-medium text-lg leading-relaxed">Join thousands of Nigerian buyers already saving up to 60% by sourcing direct from China.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button onclick="App.navigate('register')" class="neon-button px-16 py-6 text-[13px]">
                        <i class="fas fa-user-plus mr-3"></i> Create Free Account
                    </button>
                    <button onclick="App.navigate('login')" class="bg-white/10 backdrop-blur-xl border border-white/20 text-white px-16 py-6 rounded-[20px] font-black text-[13px] uppercase tracking-[0.2em] hover:bg-white/20 transition-all active:scale-95">
                        Sign In
                    </button>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
.cat-pill {
    background: white;
    color: #64748b;
    border: 1.5px solid #f1f5f9;
    border-radius: 100px;
    padding: 10px 20px;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 0.02em;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    white-space: nowrap;
}
.cat-pill.active, .cat-pill:hover {
    background: #0f172a;
    color: white;
    border-color: #0f172a;
    box-shadow: 0 10px 30px rgba(15,23,42,0.3);
    transform: translateY(-2px);
}
</style>

<script>
    async function loadProducts(cat = '') {
        const res = await API.products.list(cat);
        if (!res || res.status !== 200 || !res.data.success) return;
        const products = res.data.products;

        if (products.length === 0) {
            document.getElementById('product-grid').innerHTML = `
            <div class="col-span-full text-center py-24 text-slate-300">
                <i class="fas fa-box-open text-6xl mb-6 block opacity-30"></i>
                <p class="font-black uppercase tracking-[0.4em] text-[11px]">No products in this category yet</p>
            </div>`;
            return;
        }

        document.getElementById('product-grid').innerHTML = products.map((p, idx) => `
        <div onclick="App.navigate('product?id=${p.id}')" 
             class="bg-white rounded-[28px] overflow-hidden border border-slate-50 shadow-[0_10px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_30px_80px_rgba(16,185,129,0.1)] hover:-translate-y-3 hover:border-emerald-200/50 transition-all duration-500 cursor-pointer group"
             style="animation-delay:${idx*0.04}s">
            <div class="aspect-square bg-slate-50 relative overflow-hidden">
                ${p.image_url 
                    ? `<img src="${p.image_url}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[1.5s]">`
                    : `<div class="w-full h-full flex items-center justify-center"><i class="fas fa-cube text-slate-100 text-6xl"></i></div>`}
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <button onclick="event.stopPropagation(); addToCart(${JSON.stringify(p).replace(/"/g, '&quot;')})" 
                        class="absolute bottom-4 right-4 w-11 h-11 bg-white rounded-xl flex items-center justify-center text-slate-900 shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-emerald-500 hover:text-white active:scale-90">
                    <i class="fas fa-plus text-sm"></i>
                </button>
            </div>
            <div class="p-5">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1.5">${p.category || 'General'}</p>
                <h3 class="font-black text-slate-900 text-[15px] tracking-tight leading-tight line-clamp-2 mb-3 group-hover:text-emerald-600 transition-colors">${p.name}</h3>
                <div class="flex items-center justify-between">
                    <p class="text-xl font-black text-slate-950 tracking-tighter">₦${parseFloat(p.price_naira).toLocaleString()}</p>
                    <div class="flex items-center gap-1 text-amber-400">
                        <i class="fas fa-star text-[10px]"></i>
                        <span class="text-[10px] font-black text-slate-400">4.8</span>
                    </div>
                </div>
            </div>
        </div>`).join('');
    }

    window.filterCat = (cat) => {
        document.querySelectorAll('.cat-pill').forEach(b => {
            b.classList.remove('active');
            const text = b.textContent.toLowerCase();
            if (cat === '' && text.includes('all')) b.classList.add('active');
            else if (cat && text.includes(cat.toLowerCase())) b.classList.add('active');
        });
        loadProducts(cat);
    };

    window.searchProducts = () => {
        const q = document.getElementById('home-search').value.trim();
        if (q) showToast(`Searching for "${q}"...`, 'success');
    };

    window.addToCart = (product) => {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const idx = cart.findIndex(i => i.id === product.id);
        if (idx > -1) cart[idx].quantity += 1;
        else cart.push({ ...product, quantity: 1 });
        localStorage.setItem('cart', JSON.stringify(cart));
        App.updateCartBadge('cart-badge');
        showToast(`${product.name} added to cart`, 'success');
    };

    loadProducts();
</script>
