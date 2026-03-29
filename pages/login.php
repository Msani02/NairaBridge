<script>
    App.updateHeader('<i class="fas fa-atom text-emerald-500 md:hidden animate-spin-slow"></i> <span class="md:hidden font-black tracking-tighter">Quantum Link</span>', '', false);
</script>

<div class="flex flex-col md:flex-row h-full w-full bg-transparent relative overflow-hidden min-h-[85vh] md:min-h-0 md:h-[650px] lg:h-[750px] md:mt-10 md:rounded-[56px] md:shadow-[0_60px_120px_rgba(0,0,0,0.2)] border border-transparent md:border-white/20 max-w-6xl mx-auto fade-in p-0 quantum-glass backdrop-blur-3xl">
    
    <!-- Left Promotional Panel -->
    <div class="hidden md:flex md:w-1/2 lg:w-[55%] bg-slate-950 p-12 lg:p-20 flex-col justify-between relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-slate-950/80 to-teal-950/40"></div>
        <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
        
        <div class="relative z-10 w-full max-w-lg space-y-8">
            <h1 class="text-5xl lg:text-7xl font-black text-white tracking-tighter flex items-center gap-4 leading-none">
                <i class="fas fa-atom text-emerald-400 animate-spin-slow"></i> Quantum
            </h1>
            <p class="text-emerald-50/60 mt-5 text-xl font-medium leading-relaxed">Secured cross-border procurement connecting verified Chinese suppliers with elite Nigerian logistics nodes.</p>
        </div>
        
        <div class="relative z-10 mt-12 w-full max-w-lg">
            <div class="bg-white/5 backdrop-blur-2xl rounded-[32px] p-8 border border-white/10 shadow-2xl hover:-translate-y-1 transition-transform">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-emerald-400 mb-4 opacity-80">Network Status</p>
                <div class="flex flex-wrap items-center gap-8">
                    <span class="flex items-center gap-3 text-white font-black text-sm uppercase tracking-widest"><i class="fas fa-shield-check text-emerald-400"></i> Encrypted</span>
                    <span class="w-1.5 h-1.5 bg-white/20 rounded-full hidden xl:inline-block"></span>
                    <span class="flex items-center gap-3 text-white font-black text-sm uppercase tracking-widest"><i class="fas fa-bolt text-amber-400"></i> Optimized</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="w-full md:w-1/2 lg:w-[45%] px-8 py-12 md:px-16 lg:px-20 flex flex-col justify-center items-center md:items-start h-full relative z-10 bg-white/40 slide-up flex-shrink-0">
        
        <div class="w-full max-w-sm mx-auto md:mx-0 translate-z-10 relative">
            <div class="text-center md:text-left mb-12">
                <div class="w-24 h-24 bg-slate-950 rounded-[32px] flex items-center justify-center mx-auto md:mx-0 mb-8 text-emerald-400 text-4xl shadow-2xl group hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-fingerprint animate-pulse"></i>
                </div>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tighter leading-none">Access <br> <span class="text-emerald-500/80">Terminal</span></h2>
                <p class="text-[11px] font-black text-slate-400 mt-4 uppercase tracking-[0.3em]">Authorize Presence</p>
            </div>

            <form id="frm-login" onsubmit="loginUser(event)" class="space-y-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-700 uppercase tracking-widest pl-1 opacity-60">Identity Descriptor</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within/input:text-emerald-500 transition-colors"><i class="fas fa-at"></i></div>
                        <input type="email" id="l-email" required class="w-full pl-14 pr-6 py-5 bg-white border-2 border-slate-100 rounded-[30px] focus:border-emerald-500/50 focus:bg-white focus:ring-8 focus:ring-emerald-500/5 transition-all text-[15px] font-bold text-slate-800 shadow-inner outline-none" placeholder="Email Identification">
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-700 uppercase tracking-widest pl-1 opacity-60">Quantum Passkey</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within/input:text-emerald-500 transition-colors"><i class="fas fa-key"></i></div>
                        <input type="password" id="l-pass" required class="w-full pl-14 pr-6 py-5 bg-white border-2 border-slate-100 rounded-[30px] focus:border-emerald-500/50 focus:bg-white focus:ring-8 focus:ring-emerald-500/5 transition-all text-[15px] font-bold text-slate-800 shadow-inner outline-none" placeholder="••••••••">
                    </div>
                </div>
                
                <button type="submit" id="btn-login" class="neon-button w-full py-6 group/btn mt-4">
                    <i class="fas fa-satellite-dish mr-3 group-hover/btn:animate-ping"></i>
                    <span>Establish Link</span>
                </button>
            </form>

            <p class="text-center md:text-left mt-12 text-[13px] font-black text-slate-400 pl-1 uppercase tracking-widest">
                Null Identity? <a href="javascript:App.navigate('register')" class="text-emerald-500 hover:text-emerald-600 transition-colors ml-2 hover:underline underline-offset-8 decoration-2 decoration-emerald-500/30">Generate Node</a>
            </p>
        </div>
    </div>
</div>

<script>
    async function loginUser(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-login');
        const oldHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-atom fa-spin text-lg"></i> Authenticating...';
        btn.disabled = true;

        const data = {
            email: document.getElementById('l-email').value,
            password: document.getElementById('l-pass').value
        };

        const res = await API.auth.login(data);
        if (res.status === 200 && res.data.success) {
            currentUser = res.data.user;
            showToast('Presence Authorized', 'success');
            if (currentUser.role === 'admin') App.navigate('admin_dashboard');
            else if (currentUser.role === 'agent') App.navigate('agent_dashboard');
            else App.navigate('home');
        } else {
            showToast(res.data.message || 'Access Denied', 'error');
            btn.innerHTML = oldHTML;
            btn.disabled = false;
        }
    }
</script>
