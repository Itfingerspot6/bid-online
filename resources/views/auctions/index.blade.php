@extends('layouts.app')

@section('title', 'Semua Lelang')

@section('content')

{{-- Hero --}}
<div class="border-b border-zinc-800 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl text-white mb-3">Platform Lelang <span class="text-amber-400">Online</span></h1>
        <p class="text-zinc-400 text-lg">Temukan barang terbaik dengan harga terbaik.</p>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('auctions.index') }}" class="px-4 py-2 rounded-full text-sm {{ !request('category') ? 'bg-amber-400 text-zinc-950 font-semibold' : 'border border-zinc-700 text-zinc-400 hover:border-zinc-500 hover:text-white' }} transition-all">
            Semua
        </a>
        @foreach($categories as $category)
            <a href="{{ route('auctions.index', ['category' => $category->slug]) }}" class="px-4 py-2 rounded-full text-sm {{ request('category') == $category->slug ? 'bg-amber-400 text-zinc-950 font-semibold' : 'border border-zinc-700 text-zinc-400 hover:border-zinc-500 hover:text-white' }} transition-all">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>

{{-- Auction Grid --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-16">
    @if($auctions->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($auctions as $auction)
                <a href="{{ route('auctions.show', $auction->slug) }}" class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all hover:-translate-y-1">
                    {{-- Image --}}
                    <div class="aspect-square bg-zinc-800 overflow-hidden">
                        @if($auction->images && count($auction->images) > 0)
                            <img src="{{ Storage::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-600">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <span class="text-xs text-zinc-500 uppercase tracking-wider">{{ $auction->category->name }}</span>
                        <h3 class="text-white font-medium mt-1 line-clamp-2 group-hover:text-amber-400 transition-colors">{{ $auction->title }}</h3>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <p class="text-xs text-zinc-500">Harga saat ini</p>
                                <p class="text-amber-400 font-semibold">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-zinc-500">Berakhir</p>
                                <p class="text-xs text-zinc-300">{{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="mt-3">
                            @if($auction->status === 'active')
                                <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                    Aktif
                                </span>
                            @elseif($auction->status === 'ended')
                                <span class="text-xs px-2 py-1 rounded-full bg-zinc-800 text-zinc-500">Selesai</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $auctions->appends(['category' => request('category')])->links() }}
        </div>
    @else
        <div class="text-center py-20 text-zinc-600">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-lg">Belum ada lelang tersedia.</p>
        </div>
    @endif
</div>

@endsection