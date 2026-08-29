<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.json">
    <title>@yield('title', 'Society Management App') - Greenfield Heights</title>

    <!-- Tailwind CSS CDN & Inter Font -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
        }
    </style>
</head>
<body class="h-full flex flex-col antialiased bg-slate-950 text-slate-100 pb-24 md:pb-6">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 glass-panel border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Mobile Toggle -->
                <div class="flex items-center space-x-3">
                    <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg text-slate-300 hover:bg-slate-800 focus:outline-none">
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>

                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <i data-feather="home" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-base text-white tracking-tight block leading-none">Greenfield<span class="text-blue-400">Heights</span></span>
                            <span class="text-[9px] uppercase font-bold tracking-widest text-slate-400">Society Portal</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="grid" class="w-3.5 h-3.5 inline mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('members.index') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('members.*') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="users" class="w-3.5 h-3.5 inline mr-1"></i> Members
                    </a>
                    <a href="{{ route('maintenance.index') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('maintenance.*') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="credit-card" class="w-3.5 h-3.5 inline mr-1"></i> Maintenance
                    </a>
                    <a href="{{ route('notices.index') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('notices.*') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="bell" class="w-3.5 h-3.5 inline mr-1"></i> Notices
                    </a>
                    <a href="{{ route('complaints.index') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('complaints.*') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="life-buoy" class="w-3.5 h-3.5 inline mr-1"></i> Helpdesk
                    </a>
                    <a href="{{ route('visitors.index') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('visitors.*') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="shield" class="w-3.5 h-3.5 inline mr-1"></i> Visitors
                    </a>
                    <a href="{{ route('amenities.index') }}" class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('amenities.*') ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="calendar" class="w-3.5 h-3.5 inline mr-1"></i> Amenities
                    </a>
                </nav>

                <!-- Right Actions: PWA Install + User Profile -->
                <div class="flex items-center space-x-2">
                    <button id="pwa-install-btn" class="hidden items-center space-x-1 px-2.5 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-lg text-xs font-bold transition">
                        <i data-feather="download" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Install App</span>
                    </button>

                    @auth
                        <div class="flex items-center space-x-2 border-l border-slate-800 pl-2">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold text-white leading-snug">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-semibold text-blue-400 capitalize">
                                    {{ Auth::user()->role }} {{ Auth::user()->flat ? '• Flat ' . Auth::user()->flat->flat_number : '' }}
                                </div>
                            </div>
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" title="Logout" class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                                    <i data-feather="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>

            </div>
        </div>

        <!-- Mobile Slide-Down Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 bg-slate-900/95 px-4 py-3 space-y-2 text-xs font-semibold">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="grid" class="w-4 h-4 inline mr-2 text-blue-400"></i> Dashboard
            </a>
            <a href="{{ route('members.index') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="users" class="w-4 h-4 inline mr-2 text-blue-400"></i> Society Members
            </a>
            <a href="{{ route('maintenance.index') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="credit-card" class="w-4 h-4 inline mr-2 text-emerald-400"></i> Maintenance & Bills
            </a>
            <a href="{{ route('visitors.index') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="shield" class="w-4 h-4 inline mr-2 text-amber-400"></i> Visitor Pass / Gate
            </a>
            <a href="{{ route('complaints.index') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="life-buoy" class="w-4 h-4 inline mr-2 text-rose-400"></i> Helpdesk Tickets
            </a>
            <a href="{{ route('notices.index') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="bell" class="w-4 h-4 inline mr-2 text-amber-400"></i> Digital Notice Board
            </a>
            <a href="{{ route('amenities.index') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-slate-800">
                <i data-feather="calendar" class="w-4 h-4 inline mr-2 text-cyan-400"></i> Book Amenities
            </a>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center justify-between p-4 mb-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm">
                <div class="flex items-center space-x-2">
                    <i data-feather="check-circle" class="w-5 h-5 text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-center justify-between p-4 mb-3 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-300 text-sm">
                <div class="flex items-center space-x-2">
                    <i data-feather="info" class="w-5 h-5 text-blue-400"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-blue-400 hover:text-blue-200">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm">
                <div class="flex items-center space-x-2 font-bold mb-1">
                    <i data-feather="alert-triangle" class="w-5 h-5 text-rose-400"></i>
                    <span>Please correct the following:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-300 pl-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Bottom Mobile App Navigation Bar (Fixed at bottom for smartphones) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 glass-panel border-t border-slate-800/90 py-2 px-2 shadow-2xl">
        <div class="grid grid-cols-6 text-center">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 text-[10px] font-bold {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-slate-400' }}">
                <i data-feather="grid" class="w-4 h-4 mb-0.5"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('members.index') }}" class="flex flex-col items-center py-1 text-[10px] font-bold {{ request()->routeIs('members.*') ? 'text-blue-400' : 'text-slate-400' }}">
                <i data-feather="users" class="w-4 h-4 mb-0.5"></i>
                <span>Members</span>
            </a>
            <a href="{{ route('maintenance.index') }}" class="flex flex-col items-center py-1 text-[10px] font-bold {{ request()->routeIs('maintenance.*') ? 'text-blue-400' : 'text-slate-400' }}">
                <i data-feather="credit-card" class="w-4 h-4 mb-0.5"></i>
                <span>Bills</span>
            </a>
            <a href="{{ route('visitors.index') }}" class="flex flex-col items-center py-1 text-[10px] font-bold {{ request()->routeIs('visitors.*') ? 'text-blue-400' : 'text-slate-400' }}">
                <i data-feather="shield" class="w-4 h-4 mb-0.5"></i>
                <span>Gate</span>
            </a>
            <a href="{{ route('complaints.index') }}" class="flex flex-col items-center py-1 text-[10px] font-bold {{ request()->routeIs('complaints.*') ? 'text-blue-400' : 'text-slate-400' }}">
                <i data-feather="life-buoy" class="w-4 h-4 mb-0.5"></i>
                <span>Support</span>
            </a>
            <a href="{{ route('notices.index') }}" class="flex flex-col items-center py-1 text-[10px] font-bold {{ request()->routeIs('notices.*') ? 'text-blue-400' : 'text-slate-400' }}">
                <i data-feather="bell" class="w-4 h-4 mb-0.5"></i>
                <span>Notices</span>
            </a>
        </div>
    </nav>

    <!-- PWA Service Worker & UI Helpers -->
    <script>
        feather.replace();

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('[PWA] Service Worker registered:', reg.scope))
                    .catch(err => console.error('[PWA] Service Worker failed:', err));
            });
        }

        // PWA Install Prompt Handler
        let deferredPrompt;
        const installBtn = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installBtn) {
                installBtn.classList.remove('hidden');
                installBtn.classList.add('flex');
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`PWA install choice: ${outcome}`);
                    deferredPrompt = null;
                    installBtn.classList.add('hidden');
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
