<script>
    App.updateHeader('<a href="javascript:App.navigate(\'login\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></a>', '<span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] quantum-glass px-5 py-2.5 rounded-xl border border-white/50">Node Initialization</span>', false);
</script>

<div class="flex flex-col md:flex-row-reverse h-full w-full bg-transparent relative overflow-hidden min-h-[85vh] md:min-h-0 md:h-[700px] lg:h-[780px] md:mt-10 md:rounded-[56px] md:shadow-[0_60px_120px_rgba(0,0,0,0.2)] border border-transparent md:border-white/20 max-w-6xl mx-auto fade-in p-0 quantum-glass backdrop-blur-3xl">
    
    <!-- Right Promo Panel -->
    <div class="hidden md:flex md:w-1/2 lg:w-[55%] bg-slate-950 p-12 lg:p-20 flex-col justify-between relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-bl from-teal-600/20 via-slate-950/80 to-emerald-950/40"></div>
        <div class="absolute -right-20 -bottom-20 w-[500px] h-[500px] bg-teal-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
        
        <div class="relative z-10 space-y-8">
            <h1 class="text-5xl lg:text-7xl font-black text-white tracking-tighter flex items-center gap-4 leading-none">
                <i class="fas fa-network-wired text-teal-400"></i> Network
            </h1>
            <p class="text-teal-50/60 text-xl font-medium leading-relaxed">Establish your digital footprint to seamlessly coordinate global shipments through the NairaBridge Quantum Protocol.</p>
        </div>
        
        <div class="relative z-10">
            <div class="bg-white/5 backdrop-blur-2xl rounded-[32px] p-8 border border-white/10 shadow-2xl hover:-translate-y-1 transition-transform">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-teal-400 mb-4 opacity-80">Encryption Level</p>
                <div class="flex flex-wrap items-center gap-8">
                    <span class="flex items-center gap-3 text-white font-black text-sm uppercase tracking-widest"><i class="fas fa-user-shield text-teal-400"></i> Authenticated</span>
                    <span class="flex items-center gap-3 text-white font-black text-sm uppercase tracking-widest"><i class="fas fa-lock text-emerald-400"></i> 256-AES</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Left Form Panel -->
    <div class="w-full md:w-1/2 lg:w-[45%] px-8 py-12 md:px-16 lg:px-20 flex flex-col justify-center items-center md:items-start h-full relative z-10 bg-white/40 slide-up flex-shrink-0 overflow-y-auto">
        
        <div class="w-full max-w-sm mx-auto md:mx-0">
            <div class="text-center md:text-left mb-10">
                <div class="w-24 h-24 bg-slate-950 rounded-[32px] flex items-center justify-center mx-auto md:mx-0 mb-8 text-teal-400 text-4xl shadow-2xl hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tighter leading-none">Initialize <br><span class="text-teal-500/80">Identity</span></h2>
                <p class="text-[11px] font-black text-slate-400 mt-4 uppercase tracking-[0.3em]">Quantum Node Genesis</p>
            </div>

            <form id="frm-reg" onsubmit="registerUser(event)" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Full Designation</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within/input:text-teal-500 transition-colors"><i class="fas fa-user"></i></div>
                        <input type="text" id="r-name" required class="w-full pl-14 pr-6 py-5 bg-white border-2 border-slate-100 rounded-[30px] focus:border-teal-500/50 focus:ring-8 focus:ring-teal-500/5 transition-all text-[15px] font-bold text-slate-800 shadow-inner outline-none" placeholder="John Doe">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Email Identity</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within/input:text-teal-500 transition-colors"><i class="fas fa-at"></i></div>
                        <input type="email" id="r-email" required class="w-full pl-14 pr-6 py-5 bg-white border-2 border-slate-100 rounded-[30px] focus:border-teal-500/50 focus:ring-8 focus:ring-teal-500/5 transition-all text-[15px] font-bold text-slate-800 shadow-inner outline-none" placeholder="you@example.com">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Passkey</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 group-focus-within/input:text-teal-500 transition-colors"><i class="fas fa-key"></i></div>
                            <input type="password" id="r-pass" required class="w-full pl-12 pr-4 py-5 bg-white border-2 border-slate-100 rounded-[30px] focus:border-teal-500/50 focus:ring-8 focus:ring-teal-500/5 transition-all text-[14px] font-bold text-slate-800 shadow-inner outline-none" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Node Role</label>
                        <div class="relative">
                            <select id="r-role" class="w-full pl-5 pr-10 py-5 bg-white border-2 border-slate-100 rounded-[30px] focus:border-teal-500/50 transition-all text-[13px] font-black text-slate-800 shadow-inner appearance-none cursor-pointer outline-none">
                                <option value="user">Elite Buyer</option>
                                <option value="agent">Strategic Agent</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-300"><i class="fas fa-chevron-down"></i></div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" id="btn-reg" class="neon-button w-full py-6 group/btn mt-2" style="background: var(--quantum-slate);">
                    <i class="fas fa-bolt mr-3 group-hover/btn:animate-bounce text-teal-400 group-hover/btn:text-white transition-colors"></i>
                    <span>Activate Node</span>
                </button>
            </form>

            <p class="text-center md:text-left mt-10 text-[13px] font-black text-slate-400 pl-1 uppercase tracking-widest">
                Existing node? <a href="javascript:App.navigate('login')" class="text-teal-500 hover:text-teal-600 transition-colors ml-2 hover:underline underline-offset-8 decoration-2 decoration-teal-500/30">Link Terminal</a>
            </p>
        </div>
    </div>
</div>

<script>
    async function registerUser(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-reg');
        const oldHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-atom fa-spin text-lg"></i> Initializing Node...';
        btn.disabled = true;

        const data = {
            name: document.getElementById('r-name').value,
            email: document.getElementById('r-email').value,
            password: document.getElementById('r-pass').value,
            role: document.getElementById('r-role').value
        };

        const res = await API.auth.register(data);
        if (res.status === 200 && res.data.success) {
            showToast('Node Initialized. Linking to terminal...', 'success');
            setTimeout(() => App.navigate('login'), 1500);
        } else {
            showToast(res.data.message || 'Initialization failed', 'error');
            btn.innerHTML = oldHTML;
            btn.disabled = false;
        }
    }
</script>
