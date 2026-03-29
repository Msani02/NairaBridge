<script>
    App.updateHeader('<i class="fas fa-fingerprint text-emerald-400"></i> Identity Node', '<button onclick="logout()" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-power-off text-lg"></i></button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-5xl mx-auto w-full space-y-12">
    
    <!-- Identity Profile Card -->
    <div class="quantum-glass p-12 md:p-20 rounded-[56px] border border-white/50 shadow-[0_60px_120px_-20px_rgba(0,0,0,0.1)] relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none"></div>
        <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-emerald-500 opacity-5 rounded-full blur-[100px] group-hover:opacity-10 transition-opacity duration-[3s]"></div>
        
        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16 relative z-10">
            <div class="relative group/avatar">
                <div class="absolute -inset-1 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-[40px] blur opacity-20 group-hover/avatar:opacity-50 transition-opacity"></div>
                <div class="w-32 h-32 md:w-48 md:h-48 bg-slate-900 text-white rounded-[40px] flex items-center justify-center text-5xl md:text-7xl font-black shadow-2xl relative overflow-hidden">
                    <span id="p-initials">...</span>
                    <!-- Optional: <img src="p-avatar-url" class="absolute inset-0 w-full h-full object-cover"> -->
                </div>
            </div>

            <div class="text-center md:text-left space-y-4">
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3">
                    <span class="px-5 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm" id="p-role">Verified Role</span>
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest px-4 py-1.5 border border-slate-50 rounded-xl shadow-inner">Member Since 2024</span>
                </div>
                <h1 id="p-name" class="text-5xl md:text-7xl font-black text-slate-950 tracking-tighter leading-tight quantum-title">Identity Name</h1>
                <p id="p-email" class="text-slate-400 font-medium text-lg leading-relaxed">identity@nairabridge.node</p>
            </div>
        </div>
    </div>

    <!-- Tier Settings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 slide-up" style="animation-delay: 0.1s;">
        <div class="quantum-card p-10 group hover:border-emerald-500/20 cursor-pointer" onclick="openProfileUpdateModal()">
            <div class="flex items-center gap-6 mb-8">
                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all shadow-inner">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-[17px] font-black text-slate-950 tracking-tight">Identity Metadata</h4>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Update naming & profile data</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-slate-200 group-hover:text-emerald-400 absolute right-10 bottom-10 transition-colors"></i>
        </div>

        <div class="quantum-card p-10 group hover:border-indigo-500/20 cursor-pointer" onclick="openPasskeyUpdateModal()">
            <div class="flex items-center gap-6 mb-8">
                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-all shadow-inner">
                    <i class="fas fa-key text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-[17px] font-black text-slate-950 tracking-tight">Quantum Passkey</h4>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Secure identity credentials</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-slate-200 group-hover:text-indigo-400 absolute right-10 bottom-10 transition-colors"></i>
        </div>
    </div>

    <!-- Danger Terminal -->
    <div class="pt-8 slide-up" style="animation-delay: 0.2s;">
        <button onclick="App.showModal('Protocol: Account decommission requires global nexus authorization.')" class="w-full bg-rose-50 text-rose-500 py-6 rounded-3xl font-black text-[11px] uppercase tracking-[0.4em] border border-rose-100 hover:bg-rose-500 hover:text-white transition-all active:scale-[0.98] shadow-xl">
             Decommission Node Identity
        </button>
    </div>
</div>

<script>
    if (currentUser) {
        document.getElementById('p-name').textContent = currentUser.name;
        document.getElementById('p-email').textContent = currentUser.email;
        document.getElementById('p-role').textContent = currentUser.role.toUpperCase();
        document.getElementById('p-initials').textContent = currentUser.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
    } else {
        App.navigate('login');
    }

    async function openProfileUpdateModal() {
        App.showModal(`
            <div class="p-8 space-y-8">
                <div class="text-center md:text-left space-y-2">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Identity Registry</h3>
                    <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Modify Personnel Metadata</p>
                </div>
                <form onsubmit="handleProfileUpdate(event)" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Quantum Descriptor (Full Name)</label>
                        <input type="text" id="up-name" value="${currentUser.name}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[20px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-emerald-500 transition-all outline-none">
                    </div>
                    <button type="submit" class="neon-button w-full">Synchronize Data</button>
                </form>
            </div>
        `);
    }

    async function handleProfileUpdate(e) {
        e.preventDefault();
        const name = document.getElementById('up-name').value;
        const res = await API.auth.updateProfile({ name });
        if (res.status === 200 && res.data.success) {
            currentUser.name = name;
            showToast('Identity Synchronized', 'success');
            App.hideModal();
            App.navigate('profile');
        }
    }

    async function openPasskeyUpdateModal() {
        App.showModal(`
            <div class="p-8 space-y-8">
                <div class="text-center md:text-left space-y-2">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Passkey Recalibration</h3>
                    <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Security Credentials Protocol</p>
                </div>
                <form onsubmit="App.hideModal(); showToast('Passkey Transmitted', 'success'); event.preventDefault();" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">New Identity Passkey</label>
                        <input type="password" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[20px] py-4 px-6 text-[14px] font-bold text-slate-900 focus:border-indigo-500 transition-all outline-none" placeholder="••••••••">
                    </div>
                    <button type="submit" class="neon-button w-full" style="background-color: var(--quantum-slate)">Log New Protocol</button>
                </form>
            </div>
        `);
    }
</script>
