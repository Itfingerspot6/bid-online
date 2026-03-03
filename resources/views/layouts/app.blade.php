<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BidOnline') }} - @yield('title', 'Platform Lelang Online')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#09090b] text-zinc-100 min-h-screen selection:bg-amber-400 selection:text-zinc-950">

    <nav class="border-b border-white/5 bg-zinc-950/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(251,191,36,0.2)] group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-zinc-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1 m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9 M6 7l6-2 m6 2l3-1 m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9 m-3-9l-6-2 m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-2xl text-white tracking-tight">Bid<span class="text-amber-400">Online</span></span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-10">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    @auth
                        <a href="{{ route('auctions.index') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors relative group">
                            Lelang
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors relative group">
                            Dashboard
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors relative group">
                            Transaksi
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors relative group">
                            Profil
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    @endauth
                </div>

                <div class="flex items-center gap-6">
                    @auth
                        <div class="hidden lg:flex flex-col items-end">
                            <span class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Saldo Tersedia</span>
                            <span class="text-sm font-bold text-white">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-4 pl-4 border-l border-white/5">
                            <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-xl border border-amber-400/20 p-0.5 bg-zinc-900 group hover:border-amber-400/50 transition-all">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover rounded-[0.5rem]">
                                @else
                                    <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-zinc-500 group-hover:text-amber-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-outline !py-2 !px-4 text-xs font-bold uppercase tracking-wider">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-zinc-400 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-6 py-4 rounded-2xl text-sm flex items-center gap-3 backdrop-blur-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-2xl text-sm flex items-center gap-3 backdrop-blur-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="border-t border-white/5 mt-20 py-12 bg-zinc-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center items-center gap-2 mb-4 opacity-50">
                <span class="font-display text-xl text-white tracking-tight">Bid<span class="text-amber-400">Online</span></span>
            </div>
            <p class="text-zinc-600 text-sm tracking-wide">
                © {{ date('Y') }} BidOnline. Platform Lelang Online Terpercaya.
            </p>
        </div>
    </footer>

</body>
</html>
