@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Header --}}
    <div class="relative mb-12 py-10">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/10 to-transparent blur-3xl -z-10 opacity-30"></div>
        <h1 class="font-display text-5xl text-white mb-3 tracking-tight">Dashboard</h1>
        <p class="text-zinc-400 text-lg">Selamat datang kembali, <span class="text-white font-semibold">{{ auth()->user()->name }}</span> 👋</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-16">
        <div class="glass-card stat-card-gradient-1 p-8 rounded-3xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-2">Saldo Anda</p>
            <p class="text-3xl font-bold text-white tracking-tight">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
            <div class="mt-4 flex items-center gap-2 text-xs text-amber-400/60">
                <span class="w-1 h-1 rounded-full bg-amber-400"></span>
                Siap untuk bid lelang baru
            </div>
        </div>

        <div class="glass-card stat-card-gradient-2 p-8 rounded-3xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <p class="text-xs font-bold text-teal-400 uppercase tracking-widest mb-2">Item Terpasang</p>
            <p class="text-3xl font-bold text-white tracking-tight">{{ $myAuctions->count() }} <span class="text-sm font-normal text-zinc-500">Lelang</span></p>
            <div class="mt-4 flex items-center gap-2 text-xs text-teal-400/60">
                <span class="w-1 h-1 rounded-full bg-teal-400"></span>
                Kelola barang jualan Anda
            </div>
        </div>

        <div class="glass-card stat-card-gradient-3 p-8 rounded-3xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <p class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-2">Penawaran Aktif</p>
            <p class="text-3xl font-bold text-white tracking-tight">{{ $myBids->count() }} <span class="text-sm font-normal text-zinc-500">Bids</span></p>
            <div class="mt-4 flex items-center gap-2 text-xs text-purple-400/60">
                <span class="w-1 h-1 rounded-full bg-purple-400"></span>
                Pantau status bid Anda
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        {{-- Lelang Saya --}}
        <div>
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-display text-2xl text-white tracking-tight">Lelang Saya</h2>
                <a href="{{ route('auctions.create') }}" class="btn-primary !text-[10px] !py-2 !px-4 uppercase font-black tracking-widest">
                    + Buat Baru
                </a>
            </div>

            @if($myAuctions->count() > 0)
                <div class="space-y-4">
                    @foreach($myAuctions as $auction)
                        <a href="{{ route('auctions.show', $auction->slug) }}" class="flex items-center gap-4 glass-card glass-card-hover p-4 rounded-2xl group">
                            <div class="w-16 h-16 rounded-xl overflow-hidden shadow-xl flex-shrink-0 relative">
                                @if($auction->images && count($auction->images) > 0)
                                    <img src="{{ Storage::url($auction->images[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-zinc-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-bold text-sm truncate group-hover:text-amber-400 transition-colors">{{ $auction->title }}</p>
                                <div class="flex items-center gap-3 mt-1">
                                    <p class="text-amber-400 font-bold text-xs">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                                    <span class="text-zinc-600 text-[10px] uppercase font-black tracking-widest">{{ $auction->status }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white/5 border border-white/5 group-hover:border-amber-400/50 group-hover:text-amber-400 transition-all flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="glass-card p-10 rounded-3xl text-center border-dashed border-zinc-800 h-64 flex flex-col items-center justify-center">
                    <p class="text-zinc-500 text-sm mb-4">Anda belum mulai menjual.</p>
                    <a href="{{ route('auctions.create') }}" class="text-amber-400 text-[10px] uppercase font-black tracking-widest hover:text-amber-300 transition-colors">Ayo Jual Sekarang</a>
                </div>
            @endif
        </div>

        {{-- Bid Saya --}}
        <div>
            <h2 class="font-display text-2xl text-white tracking-tight mb-8">Bid Saya</h2>

            @if($myBids->count() > 0)
                <div class="space-y-4">
                    @foreach($myBids as $bid)
                        <a href="{{ route('auctions.show', $bid->auction->slug) }}" class="flex items-center gap-4 glass-card glass-card-hover p-4 rounded-2xl group">
                            <div class="w-16 h-16 rounded-xl overflow-hidden shadow-xl flex-shrink-0">
                                @if($bid->auction->images && count($bid->auction->images) > 0)
                                    <img src="{{ Storage::url($bid->auction->images[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-zinc-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-bold text-sm truncate group-hover:text-amber-400 transition-colors">{{ $bid->auction->title }}</p>
                                <p class="text-amber-400 font-bold text-xs mt-1">Bid: Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                                <div class="mt-2">
                                    @if($bid->status === 'pending')
                                        <span class="text-[9px] uppercase tracking-widest font-black px-2 py-0.5 bg-yellow-400/10 text-yellow-500 border border-yellow-400/20 rounded-full">Pending</span>
                                    @elseif($bid->status === 'approved')
                                        <span class="text-[9px] uppercase tracking-widest font-black px-2 py-0.5 bg-green-400/10 text-green-500 border border-green-400/20 rounded-full">Approved</span>
                                    @else
                                        <span class="text-[9px] uppercase tracking-widest font-black px-2 py-0.5 bg-red-400/10 text-red-500 border border-red-400/20 rounded-full">Rejected</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white/5 border border-white/5 group-hover:border-amber-400/50 group-hover:text-amber-400 transition-all flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="glass-card p-10 rounded-3xl text-center border-dashed border-zinc-800 h-64 flex flex-col items-center justify-center">
                    <p class="text-zinc-500 text-sm mb-4">Belum ada bid aktif.</p>
                    <a href="{{ route('home') }}" class="text-amber-400 text-[10px] uppercase font-black tracking-widest hover:text-amber-300 transition-colors">Cari Barang Terarik</a>
                </div>
            @endif
        </div>

        {{-- Lelang Disimpan --}}
        <div>
            <h2 class="font-display text-2xl text-white tracking-tight mb-8">Lelang Disimpan</h2>

            @if($watchedAuctions->count() > 0)
                <div class="space-y-4">
                    @foreach($watchedAuctions as $auction)
                        <div class="relative group">
                            <a href="{{ route('auctions.show', $auction->slug) }}" class="flex items-center gap-4 glass-card glass-card-hover p-4 rounded-2xl group">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shadow-xl flex-shrink-0">
                                    @if($auction->images && count($auction->images) > 0)
                                        <img src="{{ Storage::url($auction->images[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-zinc-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-bold text-sm truncate group-hover:text-amber-400 transition-colors">{{ $auction->title }}</p>
                                    <p class="text-amber-400 font-bold text-xs mt-1">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                        <span class="text-[9px] text-zinc-500 uppercase font-black tracking-widest">{{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                            <form action="{{ route('watchlist.toggle', $auction) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500 transition-colors hover:text-white">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card p-10 rounded-3xl text-center border-dashed border-zinc-800 h-64 flex flex-col items-center justify-center">
                    <p class="text-zinc-500 text-sm mb-4">Kamu belum punya lelang favorit.</p>
                    <a href="{{ route('auctions.index') }}" class="text-amber-400 text-[10px] uppercase font-black tracking-widest hover:text-amber-300 transition-colors">Telusuri Lelang</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
