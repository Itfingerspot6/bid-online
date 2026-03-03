@extends('layouts.app')

@section('title', 'Semua Lelang')

@section('content')

{{-- Header --}}
<div class="border-b border-white/5 bg-zinc-950 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="font-display text-5xl text-white mb-3 tracking-tight">Temukan <span class="text-amber-400">Lelang</span> Impian</h1>
                <p class="text-zinc-500 text-lg">Ribuan barang unik tersedia setiap hari.</p>
            </div>
            
            {{-- Search Bar --}}
            <form action="{{ route('auctions.index') }}" method="GET" class="w-full md:w-96 relative group">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang lelang..." class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-amber-400 transition-all pl-14 group-focus-within:shadow-[0_0_20px_rgba(251,191,36,0.1)]">
                <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-500 group-focus-within:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pb-24">
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- Sidebar Filter --}}
        <aside class="w-full lg:w-72 space-y-8">
            <div class="glass-card p-6 rounded-[2rem] sticky top-24">
                <h2 class="text-white font-bold mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter & Urutan
                </h2>

                <form action="{{ route('auctions.index') }}" method="GET" class="space-y-8">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif

                    {{-- Sort --}}
                    <div>
                        <label class="text-[10px] text-zinc-500 uppercase font-black tracking-widest block mb-3">Urutkan</label>
                        <select name="sort" onchange="this.form.submit()" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-sm text-zinc-300 focus:outline-none focus:border-amber-400">
                            <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>⌛ Berakhir Segera</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>✨ Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💎 Harga Tertinggi</option>
                        </select>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="text-[10px] text-zinc-500 uppercase font-black tracking-widest block mb-3">Kategori</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 group cursor-pointer">
                                <input type="radio" name="category" value="" onchange="this.form.submit()" class="hidden peer" {{ !request('category') ? 'checked' : '' }}>
                                <div class="w-4 h-4 rounded-full border border-zinc-700 peer-checked:border-[5px] peer-checked:border-amber-400 transition-all"></div>
                                <span class="text-sm text-zinc-400 group-hover:text-white transition-colors peer-checked:text-white font-medium">Semua Kategori</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-3 group cursor-pointer">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" onchange="this.form.submit()" class="hidden peer" {{ request('category') == $cat->slug ? 'checked' : '' }}>
                                    <div class="w-4 h-4 rounded-full border border-zinc-700 peer-checked:border-[5px] peer-checked:border-amber-400 transition-all"></div>
                                    <span class="text-sm text-zinc-400 group-hover:text-white transition-colors peer-checked:text-white font-medium">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Range --}}
                    <div>
                        <label class="text-[10px] text-zinc-500 uppercase font-black tracking-widest block mb-3">Rentang Harga (Rp)</label>
                        <div class="space-y-3">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-amber-400">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-amber-400">
                            <button type="submit" class="w-full py-2 bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl hover:bg-amber-300 transition-colors mt-2">Terapkan</button>
                            @if(request()->anyFilled(['q', 'category', 'min_price', 'max_price', 'sort']))
                                <a href="{{ route('auctions.index') }}" class="block text-center text-[10px] text-zinc-500 hover:text-white transition-colors uppercase font-bold mt-4">Bersihkan Filter</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Auction List --}}
        <div class="flex-1">

{{-- Auction Grid --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-16">
    @if($auctions->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($auctions as $auction)
                <a href="{{ route('auctions.show', $auction->slug) }}" class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all hover:-translate-y-1">
                    {{-- Image --}}
                    <div class="aspect-square bg-zinc-800 overflow-hidden relative">
                        @if($auction->images && count($auction->images) > 0)
                            <img src="{{ Storage::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-600">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        @auth
                            <form action="{{ route('watchlist.toggle', $auction) }}" method="POST" class="absolute top-3 right-3 z-20">
                                @csrf
                                @php
                                    $isWatched = auth()->user()->watchlists()->where('auction_id', $auction->id)->exists();
                                @endphp
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full glass-card border transition-all {{ $isWatched ? 'bg-amber-400 border-amber-400 text-zinc-950' : 'bg-black/50 border-white/10 text-white hover:bg-white/20' }}">
                                    <svg class="w-4 h-4 {{ $isWatched ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </button>
                            </form>
                        @endauth
                    </div>

                    {{-- Info --}}
                    <div class="p-4" x-data="auctionTimer('{{ $auction->end_time }}')">
                        <span class="text-[10px] text-zinc-500 uppercase tracking-widest font-black">{{ $auction->category->name }}</span>
                        <h3 class="text-white font-medium mt-1 line-clamp-2 group-hover:text-amber-400 transition-colors h-10">{{ $auction->title }}</h3>

                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-tighter">Harga saat ini</p>
                                <p class="text-amber-400 font-bold">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-tighter">Berakhir</p>
                                <p class="text-xs font-mono font-bold transition-colors" :class="isUrgent ? 'text-orange-500' : 'text-zinc-300'">
                                    <span x-text="timeLeft"></span>
                                </p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full h-1 bg-white/5 rounded-full mt-3 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-1000" :style="'width: ' + progress + '%'"></div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="mt-4 flex items-center justify-between">
                            @if($auction->status === 'active')
                                <span class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full bg-green-500/10 text-green-400 border border-green-500/20 uppercase font-bold tracking-widest">
                                    <span class="w-1 h-1 rounded-full bg-green-400 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-zinc-800 text-zinc-500 uppercase font-bold tracking-widest">Selesai</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

<script>
    function auctionTimer(endTime) {
        return {
            timeLeft: '',
            isUrgent: false,
            progress: 100,
            interval: null,

            init() {
                this.update();
                this.interval = setInterval(() => this.update(), 1000);
            },

            update() {
                const end = new Date(endTime).getTime();
                const now = new Date().getTime();
                const distance = end - now;

                if (distance < 0) {
                    this.timeLeft = 'Selesai';
                    this.progress = 0;
                    clearInterval(this.interval);
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                this.timeLeft = (d > 0 ? d + 'd ' : '') + 
                                h.toString().padStart(2, '0') + ':' + 
                                m.toString().padStart(2, '0') + ':' + 
                                s.toString().padStart(2, '0');
                
                this.isUrgent = distance < (60 * 60 * 1000);
                this.progress = Math.min(100, (distance / (24 * 60 * 60 * 1000)) * 100);
            }
        }
    }
</script>

                {{-- Pagination --}}
                <div class="mt-16">
                    {{ $auctions->links() }}
                </div>
            @else
                <div class="glass-card rounded-[3rem] py-32 text-center w-full">
                    <div class="w-24 h-24 bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-2xl text-white font-bold mb-4">Tidak ada lelang ditemukan</h3>
                    <p class="text-zinc-500 max-w-xs mx-auto">Coba sesuaikan filter atau kata kunci pencarian kamu.</p>
                    <a href="{{ route('auctions.index') }}" class="inline-block mt-8 text-amber-400 font-bold border-b border-amber-400/30 hover:border-amber-400 pb-1 transition-all">Reset Pencarian</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection