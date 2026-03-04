@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ tab: 'overview' }">
    
    {{-- Header --}}
    <div class="relative mb-12 py-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/10 to-transparent blur-3xl -z-10 opacity-30"></div>
        <div>
            <h1 class="font-display text-5xl text-white mb-3 tracking-tight">Dashboard</h1>
            <p class="text-zinc-400 text-lg">Selamat datang kembali, <span class="text-white font-semibold">{{ auth()->user()->name }}</span> 👋</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->canCreateAuction())
                <a href="{{ route('auctions.create') }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Mulai Jual Barang
                </a>
            @elseif(auth()->user()->seller_status === 'none' || auth()->user()->seller_status === 'rejected')
                <form action="{{ route('seller.request') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Daftar Jadi Penjual
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-card stat-card-gradient-1 p-6 rounded-3xl relative overflow-hidden group">
            <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-1">Saldo Anda</p>
            <p class="text-2xl font-bold text-white uppercase">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
        </div>

        <div class="glass-card stat-card-gradient-2 p-6 rounded-3xl relative overflow-hidden group border border-white/5">
            <p class="text-[10px] font-bold text-teal-400 uppercase tracking-widest mb-1">Item Terpasang</p>
            <p class="text-2xl font-bold text-white uppercase">{{ $myAuctions->count() }} <span class="text-xs font-normal text-zinc-500">Lelang</span></p>
        </div>

        <div class="glass-card stat-card-gradient-3 p-6 rounded-3xl relative overflow-hidden group border border-white/5">
            <p class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">Penawaran Aktif</p>
            <p class="text-2xl font-bold text-white uppercase">{{ $myBids->count() }} <span class="text-xs font-normal text-zinc-500">Bids</span></p>
        </div>

        <div class="glass-card bg-orange-500/10 p-6 rounded-3xl relative overflow-hidden group border border-orange-500/20">
            <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1">Calon Pendapatan</p>
            <p class="text-2xl font-bold text-white uppercase">Rp {{ number_format($potentialEarnings, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex items-center gap-2 mb-8 border-b border-white/5 overflow-x-auto no-scrollbar pb-0">
        <button @click="tab = 'overview'" :class="tab === 'overview' ? 'text-amber-400 border-amber-400 bg-amber-400/5' : 'text-zinc-500 border-transparent hover:text-white'" class="px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
            Ringkasan
        </button>
        <button @click="tab = 'seller'" :class="tab === 'seller' ? 'text-amber-400 border-amber-400 bg-amber-400/5' : 'text-zinc-500 border-transparent hover:text-white'" class="px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
            Seller Center
        </button>
        <button @click="tab = 'bids'" :class="tab === 'bids' ? 'text-amber-400 border-amber-400 bg-amber-400/5' : 'text-zinc-500 border-transparent hover:text-white'" class="px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
            Aktivitas Bid
        </button>
        <button @click="tab = 'watchlist'" :class="tab === 'watchlist' ? 'text-amber-400 border-amber-400 bg-amber-400/5' : 'text-zinc-500 border-transparent hover:text-white'" class="px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
            Disimpan
        </button>
    </div>

    {{-- Tab Contents --}}
    <div class="min-h-[400px]">
        
        {{-- TAB: Overview --}}
        <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            {{-- Seller Status Banner --}}
            @if(auth()->user()->role === 'user' && auth()->user()->seller_status !== 'none')
                <div class="mb-8 p-6 rounded-3xl border {{ auth()->user()->seller_status === 'pending' ? 'bg-amber-500/10 border-amber-500/20 text-amber-500' : 'bg-red-500/10 border-red-500/20 text-red-500' }} flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl {{ auth()->user()->seller_status === 'pending' ? 'bg-amber-500/20' : 'bg-red-500/20' }} flex items-center justify-center">
                            @if(auth()->user()->seller_status === 'pending')
                                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold">Status Penjual: {{ ucfirst(auth()->user()->seller_status) }}</p>
                            <p class="text-sm opacity-80">
                                @if(auth()->user()->seller_status === 'pending')
                                    Permintaan Anda sedang ditinjau oleh tim kami. Kami akan segera memberitahu Anda.
                                @else
                                    Maaf, permintaan Anda ditolak. Anda dapat mencoba mendaftar kembali nanti.
                                @endif
                            </p>
                        </div>
                    </div>
                    @if(auth()->user()->seller_status === 'rejected')
                        <form action="{{ route('seller.request') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-xl text-xs font-bold uppercase transition-transform active:scale-95">Daftar Ulang</button>
                        </form>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="glass-card p-8 rounded-3xl border border-white/5">
                    <h3 class="text-lg font-bold text-white mb-6">Aktivitas Terakhir</h3>
                    <div class="space-y-6">
                        @forelse($myBids->take(3) as $bid)
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-white font-medium truncate">Bid pada {{ $bid->auction->title }}</p>
                                    <p class="text-xs text-zinc-500">{{ $bid->created_at->diffForHumans() }} • Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500">Belum ada aktivitas bid.</p>
                        @endforelse
                    </div>
                </div>

                <div class="glass-card p-8 rounded-3xl border border-white/5">
                    <h3 class="text-lg font-bold text-white mb-6">Barang Jualan Baru</h3>
                    <div class="space-y-6">
                        @forelse($myAuctions->take(3) as $auction)
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-white font-medium truncate">{{ $auction->title }}</p>
                                    <p class="text-xs text-zinc-500">{{ $auction->status }} • Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500">Belum ada barang jualan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: Seller Center --}}
        <div x-show="tab === 'seller'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <div class="glass-card rounded-3xl overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">Item</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">Current Price</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">Bids</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($myAuctions as $auction)
                                <tr class="hover:bg-white/[0.02] transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-zinc-800 overflow-hidden flex-shrink-0">
                                                @if($auction->images && count($auction->images) > 0)
                                                    <img src="{{ Storage::url($auction->images[0]) }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-white truncate">{{ $auction->title }}</p>
                                                <p class="text-[10px] text-zinc-500">End: {{ $auction->end_time->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full {{ $auction->status === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                            {{ $auction->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-amber-400">
                                        Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-white">
                                        {{ $auction->bids->count() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('auctions.show', $auction->slug) }}" class="text-zinc-500 hover:text-amber-400 transition-colors">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500">Anda belum menjual barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB: My Bids --}}
        <div x-show="tab === 'bids'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($myBids as $bid)
                    <a href="{{ route('auctions.show', $bid->auction->slug) }}" class="glass-card p-6 rounded-3xl border border-white/5 hover:border-amber-400/50 transition-all group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-zinc-800 overflow-hidden flex-shrink-0">
                                @if($bid->auction->images && count($bid->auction->images) > 0)
                                    <img src="{{ Storage::url($bid->auction->images[0]) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-white truncate group-hover:text-amber-400">{{ $bid->auction->title }}</h4>
                                <p class="text-xs text-zinc-500">Bid: Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full {{ $bid->status === 'approved' ? 'bg-green-500/10 text-green-500' : 'bg-yellow-500/10 text-yellow-500' }}">
                                {{ $bid->status }}
                            </span>
                            <span class="text-[10px] text-zinc-500">{{ $bid->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-12 text-center glass-card rounded-3xl border border-dashed border-white/10">
                        <p class="text-zinc-500 text-sm">Belum ada bid aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- TAB: Watchlist --}}
        <div x-show="tab === 'watchlist'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($watchedAuctions as $auction)
                    <div class="relative group">
                        <a href="{{ route('auctions.show', $auction->slug) }}" class="glass-card p-6 rounded-3xl border border-white/5 hover:border-amber-400/50 transition-all">
                            <div class="aspect-video rounded-2xl bg-zinc-800 mb-4 overflow-hidden">
                                @if($auction->images && count($auction->images) > 0)
                                    <img src="{{ Storage::url($auction->images[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-white mb-2">{{ $auction->title }}</h4>
                            <div class="flex justify-between items-end" x-data="auctionTimer('{{ $auction->end_time }}')">
                                <div>
                                    <p class="text-[10px] text-zinc-500 uppercase font-black">Current Price</p>
                                    <p class="text-lg font-bold text-amber-400">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-zinc-500 uppercase font-bold" :class="isUrgent ? 'text-orange-500 animate-pulse' : ''" x-text="timeLeft"></p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center glass-card rounded-3xl border border-dashed border-white/10">
                        <p class="text-zinc-500 text-sm">Belum ada lelang disimpan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    if (typeof auctionTimer !== 'function') {
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
                        this.timeLeft = 'SELESAI';
                        this.progress = 0;
                        clearInterval(this.interval);
                        return;
                    }
                    const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((distance % (1000 * 60)) / 1000);
                    this.timeLeft = (d > 0 ? d + 'D ' : '') + h.toString().padStart(2, '0') + ':' + m.toString().padStart(2, '0') + ':' + s.toString().padStart(2, '0');
                    this.isUrgent = distance < (60 * 60 * 1000);
                    this.progress = Math.min(100, (distance / (24 * 60 * 60 * 1000)) * 100);
                }
            }
        }
    }
</script>
@endsection
