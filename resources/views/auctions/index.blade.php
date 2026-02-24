@extends('layouts.app')

@section('title', 'Semua Lelang')

@section('content')

{{-- Header --}}
<div class="py-12" style="border-bottom: 2px solid #EDE7D9;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl" style="color: #1B2A4A;">Semua Lelang</h1>
        <p class="mt-2" style="color: #6B7280;">Temukan barang terbaik dengan harga terbaik</p>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('auctions.index') }}"
            class="px-4 py-2 rounded-full text-sm font-medium transition-all"
            style="{{ !request('category') ? 'background-color: #1B2A4A; color: #F5F0E8;' : 'border: 2px solid #EDE7D9; color: #6B7280;' }}">
            Semua
        </a>
        @foreach($categories as $category)
            <a href="{{ route('auctions.index', ['category' => $category->slug]) }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition-all"
                style="{{ request('category') == $category->slug ? 'background-color: #1B2A4A; color: #F5F0E8;' : 'border: 2px solid #EDE7D9; color: #6B7280;' }}">
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
                <a href="{{ route('auctions.show', $auction->slug) }}"
                    class="group rounded-xl overflow-hidden transition-all hover:-translate-y-1"
                    style="background-color: white; border: 2px solid #EDE7D9; box-shadow: 0 2px 8px rgba(27,42,74,0.06);">

                    {{-- Image --}}
                    <div class="aspect-square overflow-hidden" style="background-color: #EDE7D9;">
                        @if($auction->images && count($auction->images) > 0)
                            <img src="{{ Storage::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="color: #6B7280;">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <span class="text-xs uppercase tracking-wider" style="color: #5BAD8F;">{{ $auction->category->name }}</span>
                        <h3 class="font-medium mt-1 line-clamp-2" style="color: #1B2A4A;">{{ $auction->title }}</h3>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <p class="text-xs" style="color: #6B7280;">Harga saat ini</p>
                                <p class="font-semibold" style="color: #E8836A;">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs" style="color: #6B7280;">Berakhir</p>
                                <p class="text-xs" style="color: #1B2A4A;">{{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="mt-3">
                            @if($auction->status === 'active')
                                <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background-color: rgba(91,173,143,0.1); color: #5BAD8F; border: 1px solid rgba(91,173,143,0.3);">
                                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: #5BAD8F;"></span>
                                    Aktif
                                </span>
                            @elseif($auction->status === 'ended')
                                <span class="text-xs px-2 py-1 rounded-full" style="background-color: #EDE7D9; color: #6B7280;">Selesai</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $auctions->links() }}
        </div>
    @else
        <div class="text-center py-20" style="color: #6B7280;">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-lg">Belum ada lelang tersedia.</p>
        </div>
    @endif
</div>

@endsection