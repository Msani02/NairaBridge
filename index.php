<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>NairaBridge — Elite Sourcing Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        slate: { 850: '#151e2e', 900: '#0f172a' },
                        emerald: { 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=3.1">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden selection:bg-emerald-400 selection:text-white flex flex-col h-[100dvh] w-full">
    
    <!-- Quantum Background -->
    <div id="quantum-background">
        <div class="aura-node bg-emerald-400/20 top-[-10%] left-[-10%] scale-150"></div>
        <div class="aura-node bg-teal-400/20 bottom-[-10%] right-[-10%] scale-125" style="animation-delay: -5s;"></div>
        <div class="aura-node bg-indigo-400/10 top-[40%] left-[30%] scale-110" style="animation-duration: 30s;"></div>
    </div>
    
    <!-- Full-Width Responsive Header -->
    <header id="app-header" class="quantum-glass text-slate-900 px-6 md:px-12 lg:px-24 py-4 z-20 flex justify-between items-center transition-all duration-300 w-full pt-[max(1rem,env(safe-area-inset-top))] sm:pt-4 sticky top-0 relative border-b border-white/40">
        <h1 class="text-2xl font-black tracking-tighter flex items-center gap-3 cursor-pointer gradient-text" onclick="App.navigate('home')">
            <i class="fas fa-atom text-emerald-500 animate-[spin_8s_linear_infinite]"></i> NairaBridge
        </h1>
        <div id="header-actions" class="flex gap-3 items-center"></div>
    </header>

    <div class="flex flex-1 overflow-hidden relative w-full h-full">
        <!-- Main Content Area -->
        <main id="app-content" class="flex-1 overflow-y-auto pb-28 pt-6 relative scroll-smooth no-scrollbar z-0 w-full max-w-7xl mx-auto md:px-12 lg:px-24">
            <div class="flex items-center justify-center h-full flex-col gap-6 scale-in">
                <div class="w-20 h-20 rounded-[30px] bg-white border border-slate-100 flex items-center justify-center text-emerald-400 text-4xl shadow-xl animate-pulse">
                    <i class="fas fa-atom"></i>
                </div>
                <p class="text-slate-300 font-black animate-pulse tracking-[0.5em] text-[10px] uppercase">Quantum Boot</p>
            </div>
        </main>
    </div>

    <!-- Floating Action Modals -->
    <div id="app-modal" class="absolute inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex-col items-center justify-end md:justify-center p-0 md:p-6 transition-opacity duration-300">
        <div class="bg-white w-full md:max-w-md rounded-t-[32px] md:rounded-[32px] p-6 pt-8 md:pt-6 transform transition-transform duration-500 translate-y-full md:translate-y-0 md:scale-95 shadow-[0_20px_60px_rgba(0,0,0,0.15)] relative" id="modal-content">
        </div>
        <div class="absolute top-[calc(100%-auto)] md:hidden w-12 h-1.5 bg-gray-300 rounded-full left-1/2 -translate-x-1/2 mt-3 z-[60]"></div>
    </div>

    <!-- Responsive Bottom Fixed Navigation -->
    <div class="fixed bottom-0 left-0 w-full z-30 pointer-events-none pb-[env(safe-area-inset-bottom)]">
        
        <!-- User Nav -->
        <nav id="bottom-nav" class="quantum-nav md:w-fit md:mx-auto md:rounded-[32px] md:mb-6 md:border border-b border-white/30 px-4 py-3 md:px-8 md:py-4 flex justify-around md:gap-8 items-center pointer-events-auto transition-transform duration-500 transform translate-y-0 hidden">
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group active" data-page="home">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-home text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="orders">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-box-open text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group relative" data-page="wallet">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-wallet text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="messages">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-comment-dots text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="profile">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-user-circle text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
        </nav>
        
        <!-- Agent/Admin Nav -->
        <nav id="agent-bottom-nav" class="quantum-nav md:w-fit md:mx-auto md:rounded-[32px] md:mb-6 md:border border-b border-white/30 px-4 py-3 md:px-8 md:py-4 flex justify-around md:gap-8 items-center pointer-events-auto transition-transform duration-500 absolute w-full md:relative bottom-0 left-0 hidden translate-y-0">
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="agent_dashboard">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-chart-line text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="agent_products">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-tags text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="agent_orders">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-truck-loading text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="messages">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-comments text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
            <a href="#" class="nav-btn flex flex-col items-center flex-1 md:w-20 transition-colors text-slate-500 hover:text-emerald-600 group" data-page="profile">
                <div class="nav-icon-container transition-all duration-300">
                    <i class="fas fa-user-cog text-[22px] transition-transform group-hover:scale-110"></i>
                </div>
            </a>
        </nav>
    </div>

    <!-- UI Toast Script Override -->
    <script>
        window.showToast = (message, type = 'success') => {
            const toast = document.createElement('div');
            const color = type === 'success' ? 'bg-white border-emerald-500 text-slate-800' : 'bg-white border-red-500 text-slate-800';
            toast.className = `fixed top-24 md:top-6 left-1/2 transform -translate-x-1/2 ${color} border-l-4 px-5 py-4 rounded-[16px] shadow-[0_15px_30px_rgba(0,0,0,0.08)] z-[100] flex items-center gap-4 slide-up text-[14px] font-bold max-w-[90%] sm:max-w-sm w-full transition-transform`;
            toast.style.animation = 'slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
            
            const iconBg = type === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500';
            const iconClass = type === 'success' ? 'fa-check' : 'fa-exclamation';
            
            toast.innerHTML = `
                <div class="w-10 h-10 rounded-[12px] ${iconBg} flex items-center justify-center flex-shrink-0">
                    <i class="fas ${iconClass} text-lg"></i>
                </div>
                <span class="flex-1">${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        };
    </script>
    <script src="https://newwebpay.interswitchng.com/inline-checkout.js"></script>
    <script src="assets/js/api.js?v=3.1"></script>
    <script src="assets/js/app.js?v=3.1"></script>
</body>
</html>
