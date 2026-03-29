<script>
    App.updateHeader('<i class="fas fa-comment-nodes text-emerald-400"></i> Neural Comms', '<button onclick="App.navigate(\'home\')" class="relative w-12 h-12 quantum-glass rounded-2xl flex items-center justify-center text-slate-800 hover:text-emerald-500 transition-all active:scale-95 shadow-lg"><i class="fas fa-arrow-left text-lg"></i></button>', false);
</script>

<div class="flex flex-col h-full overflow-hidden max-w-7xl mx-auto w-full fade-in">
    <!-- Chat Interface -->
    <div class="flex flex-1 overflow-hidden relative">
        
        <!-- Contact List (Hidden on mobile when chat is open) -->
        <div id="contact-panel" class="w-full md:w-80 lg:w-96 border-r border-slate-100 bg-white/50 backdrop-blur-xl flex flex-col transition-all overflow-y-auto no-scrollbar">
            <div class="p-8 space-y-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400">Thread Registry</h3>
                    <span class="text-[9px] font-black bg-emerald-100 text-emerald-600 px-3 py-1.5 rounded-full border border-emerald-200">LIVE</span>
                </div>
                <div id="contact-list" class="space-y-4">
                    <!-- Loaders -->
                    <div class="animate-pulse flex items-center gap-4 p-6 bg-white/50 rounded-3xl border border-slate-50">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl"></div>
                        <div class="flex-1 space-y-2">
                             <div class="h-4 bg-slate-100 rounded-lg w-1/2"></div>
                             <div class="h-3 bg-slate-100 rounded-lg w-1/3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Stream -->
        <div id="message-panel" class="hidden md:flex flex-1 flex-col bg-white/30 backdrop-blur-md relative">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/5 to-transparent pointer-events-none"></div>
            
            <div id="chat-header" class="px-8 py-6 border-b border-white/50 flex items-center justify-between bg-white/40 backdrop-blur-3xl relative z-10">
                <div class="flex items-center gap-4">
                    <button onclick="backToContacts()" class="md:hidden w-10 h-10 quantum-glass rounded-xl flex items-center justify-center text-slate-400"><i class="fas fa-chevron-left"></i></button>
                    <div class="w-12 h-12 bg-slate-900 text-white rounded-[18px] flex items-center justify-center font-black shadow-xl" id="active-contact-avatar">?</div>
                    <div>
                        <h4 class="text-[16px] font-black text-slate-950 tracking-tight" id="active-contact-name">Select Thread</h4>
                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Synchronized</span>
                    </div>
                </div>
            </div>

            <div id="message-stream" class="flex-1 overflow-y-auto p-8 space-y-8 no-scrollbar relative z-0">
                <div class="flex flex-col items-center justify-center h-full text-slate-300 gap-4 opacity-50">
                    <i class="fas fa-satellite-dish text-6xl animate-bounce"></i>
                    <p class="text-[11px] font-black uppercase tracking-[0.4em]">Establish Link to Transmit</p>
                </div>
            </div>

            <div class="p-8 bg-white/40 backdrop-blur-3xl border-t border-white/50 relative z-10">
                <form id="frm-msg" onsubmit="transmitQuantumMessage(event)" class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[28px] blur opacity-10 group-focus-within:opacity-30 transition-opacity"></div>
                    <div class="relative flex items-center gap-4 bg-slate-900/5 backdrop-blur-xl border border-white p-2 rounded-[24px] shadow-inner">
                        <button type="button" class="w-12 h-12 rounded-[18px] text-slate-400 hover:text-emerald-500 transition-colors"><i class="fas fa-paperclip"></i></button>
                        <input type="text" id="m-text" class="flex-1 bg-transparent border-none focus:ring-0 text-slate-900 font-bold placeholder:text-slate-300" placeholder="Type transmission...">
                        <button type="submit" class="w-12 h-12 bg-slate-950 text-white rounded-[18px] flex items-center justify-center hover:bg-emerald-600 transition-all active:scale-90 shadow-xl">
                            <i class="fas fa-paper-plane text-emerald-400"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let activeSyncId = null;
    let pollInterval = null;

    async function loadThreadRegistry() {
        const res = await API.chat.getContacts();
        if (res.status === 200 && res.data.success) {
            const contacts = res.data.contacts;
            document.getElementById('contact-list').innerHTML = contacts.map((c, idx) => `
                <div onclick="syncThread(${c.id}, '${c.name}')" class="quantum-glass p-6 rounded-[28px] border border-transparent hover:border-emerald-500/20 hover:bg-white cursor-pointer transition-all flex items-center gap-5 group relative overflow-hidden" style="animation-delay: ${idx * 0.05}s">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center font-black text-sm group-hover:bg-slate-900 group-hover:text-white group-hover:scale-105 group-hover:rotate-[-8deg] transition-all shadow-inner">
                        ${c.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-black text-slate-900 text-[15px] tracking-tight truncate">${c.name}</h4>
                        <p class="text-[11px] font-bold text-slate-400 truncate opacity-60">${c.last_message || 'Initialize link...'}</p>
                    </div>
                    ${c.unread_count > 0 ? `<span class="w-5 h-5 bg-emerald-500 text-white text-[9px] font-black rounded-full flex items-center justify-center animate-pulse">${c.unread_count}</span>` : ''}
                </div>
            `).join('');

            // Check if contactId in URL
            const params = App.getParams();
            if (params.contactId && !activeSyncId) syncThread(params.contactId, params.contactName);
        }
    }

    async function syncThread(id, name) {
        activeSyncId = id;
        document.getElementById('active-contact-name').textContent = name;
        document.getElementById('active-contact-avatar').textContent = name.charAt(0).toUpperCase();
        
        document.getElementById('contact-panel').classList.add('hidden', 'md:flex');
        document.getElementById('message-panel').classList.remove('hidden');
        document.getElementById('message-panel').classList.add('flex');

        loadStream();
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(loadStream, 5000);
    }

    async function loadStream() {
        if (!activeSyncId) return;
        const res = await API.chat.getHistory(activeSyncId);
        if (res.status === 200 && res.data.success) {
            const msgs = res.data.messages;
            document.getElementById('message-stream').innerHTML = msgs.map(m => {
                const isMe = m.sender_id == currentUser.id;
                return `
                <div class="flex ${isMe ? 'justify-end' : 'justify-start'} slide-up">
                    <div class="max-w-[80%] space-y-2">
                        <div class="${isMe ? 'bg-slate-950 text-white rounded-[24px] rounded-br-[4px]' : 'quantum-glass text-slate-900 rounded-[24px] rounded-bl-[4px] border border-white'} px-6 py-4 shadow-xl relative overflow-hidden group">
                           ${isMe ? '<div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent"></div>' : ''}
                           <p class="text-[14px] font-medium leading-relaxed relative z-10">${m.message_text}</p>
                        </div>
                        <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest ${isMe ? 'text-right' : 'text-left'}">${new Date(m.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                    </div>
                </div>
                `;
            }).join('');
            
            const stream = document.getElementById('message-stream');
            stream.scrollTop = stream.scrollHeight;
        }
    }

    window.transmitQuantumMessage = async (e) => {
        e.preventDefault();
        const text = document.getElementById('m-text').value;
        if (!text || !activeSyncId) return;

        const res = await API.chat.send({ receiver_id: activeSyncId, message_text: text, order_id: 0 });
        if (res.status === 200 && res.data.success) {
            document.getElementById('m-text').value = '';
            loadStream();
        }
    };

    window.backToContacts = () => {
        document.getElementById('contact-panel').classList.remove('hidden');
        document.getElementById('message-panel').classList.add('hidden', 'md:flex');
        if (pollInterval) clearInterval(pollInterval);
        activeSyncId = null;
    };

    loadThreadRegistry();
</script>
