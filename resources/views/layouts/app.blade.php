<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BidOnline') }} - @yield('title', 'Platform Lelang Online')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen">

    <nav class="border-b border-zinc-800 bg-zinc-950/90 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="font-display text-2xl text-amber-400">Bid</span>
                    <span class="font-display text-2xl text-white">Online</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">home</a>
                    @auth
                        <a href="{{ route('auctions.index') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">Lelang</a>
                        <a href="{{ route('dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">Dashboard</a>
                        <a href="{{ route('transactions.index') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">Transaksi</a>
                    @endauth
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <span class="text-sm text-zinc-400">
                            Saldo: <span class="text-amber-400 font-semibold">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm px-4 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-zinc-500 hover:text-white transition-all">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm px-4 py-2 text-zinc-300 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 rounded-lg bg-amber-400 text-zinc-950 font-semibold hover:bg-amber-300 transition-colors">Daftar</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-zinc-800 mt-20 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-zinc-600 text-sm">
            © {{ date('Y') }} BidOnline. Platform Lelang Online Terpercaya.
        </div>
    </footer>

</body>
</html>