// assets/js/app.js

let currentUser = null;

const App = {
    init: async () => {
        // Check session state
        const res = await API.auth.me();
        if (res.status === 200 && res.data.success) {
            currentUser = res.data.user;
        } else {
            currentUser = null;
        }
        
        App.setupNavigation();
        App.route();
    },
    
    setupNavigation: () => {
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const page = e.currentTarget.dataset.page;
                App.navigate(page);
            });
        });
        
        // Setup modal close if clicking outside content
        document.getElementById('app-modal').addEventListener('click', (e) => {
            if (e.target.id === 'app-modal') {
                App.hideModal();
            }
        });
    },

    navigate: (page) => {
        window.location.hash = page;
        App.route();
    },

    getParams: () => {
        const hash = window.location.hash.substring(1);
        const parts = hash.split('?');
        if (parts.length < 2) return {};
        const params = {};
        parts[1].split('&').forEach(p => {
            const [k, v] = p.split('=');
            params[k] = decodeURIComponent(v);
        });
        return params;
    },

    route: () => {
        let fullHash = window.location.hash.substring(1) || 'home';
        let page = fullHash.split('?')[0];
        
        // Hide/show navs based on role
        if (currentUser) {
            if (currentUser.role === 'agent') {
                document.getElementById('bottom-nav').classList.add('hidden');
                document.getElementById('agent-bottom-nav').classList.remove('hidden');
            } else if (currentUser.role === 'admin') {
                document.getElementById('bottom-nav').classList.add('hidden');
                document.getElementById('agent-bottom-nav').classList.add('hidden');
                // Admin might have its own nav, keeping hidden for now or showing standard
            } else {
                document.getElementById('bottom-nav').classList.remove('hidden');
                document.getElementById('agent-bottom-nav').classList.add('hidden');
            }
        } else {
            document.getElementById('bottom-nav').classList.add('hidden');
            document.getElementById('agent-bottom-nav').classList.add('hidden');
        }

        // Active state
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        const activeBtn = document.querySelector(`.nav-btn[data-page="${page}"]`);
        if(activeBtn) activeBtn.classList.add('active');

        // Route Guards
        const publicPages = ['home', 'login', 'register', 'product'];
        if (!currentUser && !publicPages.includes(page)) {
            App.navigate('login');
            return;
        }
        
        const content = document.getElementById('app-content');
        content.innerHTML = `
        <div class="flex items-center justify-center h-full flex-col gap-6">
            <div class="w-20 h-20 rounded-[30px] bg-white border border-slate-100 flex items-center justify-center text-emerald-400 text-4xl shadow-xl animate-pulse">
                <i class="fas fa-leaf"></i>
            </div>
            <p class="text-slate-300 font-black tracking-[0.5em] text-[10px] uppercase">Loading</p>
        </div>`;
        App.renderPage(page, content);
    },

    renderPage: async (page, container) => {
        try {
            // we will fetch PHP templates from pages directory
            const res = await fetch(`pages/${page}.php`);
            if (res.ok) {
                const html = await res.text();
                container.innerHTML = html;
                App.executeScripts(container);
            } else {
                container.innerHTML = `<div class="p-6 text-center text-red-500 mt-20">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p>Page not found (${page}).</p>
                </div>`;
            }
        } catch(e) {
            container.innerHTML = `<div class="p-6 text-center text-red-500 mt-20">Error loading page.</div>`;
        }
    },

    executeScripts: (container) => {
        // Execute any script tags inside dynamically loaded content
        Array.from(container.querySelectorAll('script')).forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    },

    showModal: (html) => {
        const modal = document.getElementById('app-modal');
        const content = document.getElementById('modal-content');
        content.innerHTML = html;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('translate-y-full');
        }, 10);
    },

    hideModal: () => {
        const modal = document.getElementById('app-modal');
        const content = document.getElementById('modal-content');
        content.classList.add('translate-y-full');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            content.innerHTML = '';
        }, 300);
    },

    updateHeader: (title, actionsHTML = '') => {
        const header = document.getElementById('app-header');
        const defaultBrand = '<i class="fas fa-leaf text-emerald-500"></i> NairaBridge';
        header.querySelector('h1').innerHTML = title || defaultBrand;
        document.getElementById('header-actions').innerHTML = actionsHTML;
    },

    updateCartBadge: (badgeId) => {
        const badge = document.getElementById(badgeId);
        if (badge) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            let count = cart.reduce((sum, item) => sum + item.quantity, 0);
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }
};

document.addEventListener('DOMContentLoaded', App.init);
window.addEventListener('hashchange', App.route);

// Mouse-tracking card glow
document.addEventListener('mousemove', (e) => {
    document.querySelectorAll('.quantum-card').forEach(card => {
        const rect = card.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width)  * 100;
        const y = ((e.clientY - rect.top)  / rect.height) * 100;
        card.style.setProperty('--mouse-x', `${x}%`);
        card.style.setProperty('--mouse-y', `${y}%`);
    });
});

// Global Notification Polling
App.startPolling = () => {
    if (!currentUser) return;
    
    // Poll every 10 seconds for notifications and messages
    setInterval(async () => {
        try {
            const res = await API.request('notifications.php?action=unread_count');
            if (res.status === 200 && res.data.success) {
                const count = res.data.count;
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 9 ? '9+' : count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }
            
            // Also poll for unread messages if on messages page
            if (window.location.hash.startsWith('#messages')) {
                // The messages page usually handles its own polling, 
                // but we can trigger a global refresh event if needed.
                window.dispatchEvent(new CustomEvent('app:poll'));
            }
        } catch (e) {
            console.warn('Polling suspended: Network disconnected');
        }
    }, 10000);
};

// Override init to include polling
const originalInit = App.init;
App.init = async () => {
    await originalInit();
    if (currentUser) {
        App.startPolling();
    }
};

// Premium Toast Notification System
window.showToast = (message, type = 'success') => {
    const existing = document.getElementById('nb-active-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'nb-active-toast';

    const isSuc = type === 'success';
    const iconBg  = isSuc ? '#ecfdf5' : '#fff1f2';
    const iconClr = isSuc ? '#10b981' : '#f43f5e';
    const border  = isSuc ? '#10b981' : '#f43f5e';
    const icon    = isSuc ? 'fa-check' : 'fa-exclamation';

    Object.assign(toast.style, {
        position:       'fixed',
        top:            '80px',
        left:           '50%',
        transform:      'translateX(-50%) translateY(-20px)',
        minWidth:       '280px',
        maxWidth:       '90vw',
        zIndex:         '1000',
        background:     '#ffffff',
        borderLeft:     `4px solid ${border}`,
        borderRadius:   '20px',
        padding:        '16px 20px',
        display:        'flex',
        alignItems:     'center',
        gap:            '14px',
        fontFamily:     "'Outfit', sans-serif",
        fontWeight:     '700',
        fontSize:       '14px',
        color:          '#0f172a',
        boxShadow:      '0 20px 60px -10px rgba(0,0,0,0.14)',
        opacity:        '0',
        transition:     'all 0.45s cubic-bezier(0.34, 1.56, 0.64, 1)',
        backdropFilter: 'blur(20px)',
    });

    toast.innerHTML = `
        <div style="width:36px;height:36px;border-radius:12px;background:${iconBg};color:${iconClr};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas ${icon}"></i>
        </div>
        <span style="flex:1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="width:28px;height:28px;border-radius:10px;border:none;background:#f1f5f9;color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-times text-xs"></i>
        </button>
    `;

    document.body.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(-50%) translateY(0)';
    });

    // Animate out
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(-16px)';
        setTimeout(() => toast.remove(), 400);
    }, 4000);
};
