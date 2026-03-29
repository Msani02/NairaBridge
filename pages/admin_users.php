<script>
    App.updateHeader('<i class="fas fa-users-viewfinder text-rose-500"></i> Population Registry', '<button onclick="App.navigate(\'admin_dashboard\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-rose-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></button>', false);
</script>

<div class="px-4 py-8 md:py-12 pb-32 h-full overflow-y-auto no-scrollbar fade-in max-w-7xl mx-auto w-full space-y-12">
    
    <!-- Hero Header -->
    <div class="px-4 flex flex-col md:flex-row justify-between items-end gap-8">
        <div>
            <div class="flex items-center gap-3 mb-4">
                 <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping shadow-[0_0_10px_#f43f5e]"></span>
                 <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Governance Node</h3>
            </div>
            <h2 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none">Identity <br><span class="text-rose-500">Registry</span></h2>
        </div>
        <div class="relative group max-w-sm w-full">
            <input type="text" id="user-search" oninput="searchUsers()" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[28px] py-6 px-8 text-[14px] font-bold text-slate-900 placeholder:text-slate-300 focus:border-rose-500/30 transition-all outline-none shadow-inner" placeholder="Analyze Identity Stream...">
            <i class="fas fa-search absolute right-8 top-1/2 -translate-y-1/2 text-slate-200 group-focus-within:text-rose-400 transition-colors"></i>
        </div>
    </div>

    <!-- Population Flux -->
    <div id="users-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4 slide-up" style="animation-delay: 0.1s;">
        <!-- Loaders -->
        <div class="quantum-card p-10 animate-pulse bg-white border border-slate-50 flex items-center gap-8">
            <div class="w-16 h-16 bg-slate-50 rounded-[24px]"></div>
            <div class="flex-1 space-y-4">
                <div class="h-5 bg-slate-50 rounded-xl w-1/3"></div>
                <div class="h-4 bg-slate-50 rounded-xl w-1/2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadIdentityRegistry() {
        const res = await API.request('admin.php?action=users');
        if (res.status === 200 && res.data.success) {
            const users = res.data.users;
            document.getElementById('users-list').innerHTML = users.map((u, idx) => {
                const roleColor = u.role === 'admin' ? 'text-rose-500 bg-rose-50 border-rose-100' : 
                                u.role === 'agent' ? 'text-emerald-500 bg-emerald-50 border-emerald-100' : 'text-indigo-500 bg-indigo-50 border-indigo-100';
                
                return `
                <div class="quantum-card p-8 group hover:border-rose-500/30 relative overflow-hidden flex items-center gap-6" style="animation-delay: ${idx * 0.05}s">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-16 h-16 bg-white rounded-[24px] border border-slate-50 shadow-inner flex items-center justify-center text-slate-300 font-black text-xl group-hover:scale-110 group-hover:bg-rose-50 group-hover:text-rose-500 transition-all">
                        ${u.name.charAt(0).toUpperCase()}
                    </div>

                    <div class="flex-1 min-w-0 space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="${roleColor} px-3 py-1 rounded-[10px] text-[8px] font-black uppercase tracking-widest border shadow-sm">${u.role}</span>
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">ID: QX-${u.id}</span>
                        </div>
                        <h4 class="text-[18px] font-black text-slate-950 tracking-tight leading-tight truncate px-0.5">${u.name}</h4>
                        <p class="text-[11px] font-bold text-slate-400 truncate px-0.5">${u.email}</p>
                    </div>

                    <button onclick="manageIdentity(${u.id})" class="w-10 h-10 quantum-glass rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 transition-all shadow-md active:scale-95 flex-shrink-0">
                        <i class="fas fa-ellipsis-v text-sm"></i>
                    </button>
                </div>
                `;
            }).join('');
        }
    }

    window.searchUsers = () => {
        const q = document.getElementById('user-search').value.toLowerCase();
        document.querySelectorAll('#users-list > div').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(q) ? 'flex' : 'none';
        });
    };

    window.manageIdentity = (id) => showToast(`Identity protocolNBX-${id} linked`, 'success');

    loadIdentityRegistry();
</script>
