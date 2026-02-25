@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="font-display text-3xl text-white mb-2">Dashboard</h1>
    <p class="text-zinc-400 mb-8">Selamat datang, {{ auth()->user()->name }}</p>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider">Saldo</p>
            <p class="text-2xl font-semibold text-amber-400 mt-1">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
        </div>
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider">Lelang Saya</p>
            <p class="text-2xl font-semibold text-white mt-1">{{ $myAuctions->count() }}</p>
        </div>
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider">Bid Aktif</p>
            <p class="text-2xl font-semibold text-white mt-1">{{ $myBids->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Lelang Saya --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display text-xl text-white">Lelang Saya</h2>
                <a href="{{ route('auctions.create') }}" class="text-sm px-4 py-2 bg-amber-400 text-zinc-950 font-semibold rounded-lg hover:bg-amber-300 transition-colors">
                    + Buat Lelang
                </a>
            </div>

            @if($myAuctions->count() > 0)
                <div class="space-y-3">
                    @foreach($myAuctions as $auction)
                        <a href="{{ route('auctions.show', $auction->slug) }}" class="flex items-center gap-4 bg-zinc-900 border border-zinc-800 rounded-xl p-4 hover:border-zinc-600 transition-all">
                            <div class="w-14 h-14 bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0">
                                @if($auction->images && count($auction->images) > 0)
                                    <img src="{{ Storage::url($auction->images[0]) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-medium truncate">{{ $auction->title }}</p>
                                <p class="text-amber-400 text-sm">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $auction->status === 'active' ? 'bg-green-500/10 text-green-400' : 'bg-zinc-800 text-zinc-500' }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 text-center text-zinc-600">
                    <p>Belum ada lelang.</p>
                    <a href="{{ route('auctions.create') }}" class="mt-3 inline-block text-sm text-amber-400 hover:text-amber-300">Buat sekarang →</a>
                </div>
            @endif
        </div>

        {{-- Bid Saya --}}
        <div>
            <h2 class="font-display text-xl text-white mb-4">Bid Saya</h2>

            @if($myBids->count() > 0)
                <div class="space-y-3">
                    @foreach($myBids as $bid)
                        <a href="{{ route('auctions.show', $bid->auction->slug) }}" class="flex items-center gap-4 bg-zinc-900 border border-zinc-800 rounded-xl p-4 hover:border-zinc-600 transition-all">
                            <div class="w-14 h-14 bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0">
                                @if($bid->auction->images && count($bid->auction->images) > 0)
                                    <img src="{{ Storage::url($bid->auction->images[0]) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-medium truncate">{{ $bid->auction->title }}</p>
                                <p class="text-amber-400 text-sm">Bid: Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                @if($bid->status === 'pending')
                                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Pending</span>
                                @elseif($bid->status === 'approved')
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">Rejected</span>
                                @endif
                                <span class="text-xs text-zinc-500">{{ $bid->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 text-center text-zinc-600">
                    <p>Belum ada bid.</p>
                    <a href="{{ route('home') }}" class="mt-3 inline-block text-sm text-amber-400 hover:text-amber-300">Lihat lelang →</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection