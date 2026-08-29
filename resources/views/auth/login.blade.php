<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.json">
    <title>Login - Greenfield Heights Society Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-box {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-slate-950 relative overflow-hidden">

    <!-- Decorative Gradient Background Glows -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-cyan-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md">
        
        <!-- Header Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-500 shadow-xl shadow-blue-500/25 mb-4">
                <i data-feather="home" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Greenfield <span class="text-blue-400">Heights</span></h1>
            <p class="text-sm text-slate-400 mt-1">Smart Society Management & Gatekeeper System</p>
        </div>

        <!-- Login Glass Card -->
        <div class="glass-box rounded-2xl p-6 sm:p-8 shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-6">Sign In to Your Account</h2>

            <!-- Quick Demo Login Bar -->
            <div class="mb-6 p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider flex items-center justify-between">
                    <span>⚡ Quick Demo Logins:</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <a href="{{ route('quick-login', 'admin') }}" class="py-2 px-2 rounded-lg bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/30 text-blue-300 text-xs font-medium text-center transition">
                        👑 Admin
                    </a>
                    <a href="{{ route('quick-login', 'resident') }}" class="py-2 px-2 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/30 text-emerald-300 text-xs font-medium text-center transition">
                        🏠 Resident
                    </a>
                    <a href="{{ route('quick-login', 'guard') }}" class="py-2 px-2 rounded-lg bg-amber-600/20 hover:bg-amber-600/30 border border-amber-500/30 text-amber-300 text-xs font-medium text-center transition">
                        🛡️ Guard
                    </a>
                </div>
            </div>

            @if(session('info'))
                <div class="mb-4 p-3 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-300 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-feather="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', 'admin@society.com') }}" required 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" 
                            placeholder="user@society.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-feather="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" name="password" value="password" required 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500">
                        <span>Remember me</span>
                    </label>
                    <span class="text-slate-500">Demo Pass: password</span>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold rounded-xl text-sm shadow-lg shadow-blue-600/30 transition transform active:scale-95 flex items-center justify-center space-x-2">
                    <span>Sign In</span>
                    <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-xs text-slate-500">
            &copy; 2026 Greenfield Heights Housing Society. Installable Progressive Web App.
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
