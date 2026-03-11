@extends('layouts.app')

@section('title', $auction->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" 
     x-data="auctionRoom({{ $auction->id }}, {{ $auction->current_price }}, {{ $auction->min_bid_increment }})">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        {{-- Images --}}
        <div>
            @if($auction->images && count($auction->images) > 0)
                <div class="aspect-square bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800">
                    <img src="{{ Storage::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover">
                </div>
                @if(count($auction->images) > 1)
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach(array_slice($auction->images, 1) as $image)
                            <div class="aspect-square bg-zinc-900 rounded-lg overflow-hidden border border-zinc-800">
                                <img src="{{ Storage::url($image) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="aspect-square bg-zinc-900 rounded-xl border border-zinc-800 flex items-center justify-center text-zinc-600">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Detail --}}
        <div>
            <span class="text-xs text-amber-400 uppercase tracking-widest">{{ $auction->category->name }}</span>
            <h1 class="font-display text-3xl text-white mt-2">{{ $auction->title }}</h1>

            <div class="mt-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if($auction->status === 'active')
                        <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                            Aktif
                        </span>
                    @elseif($auction->status === 'ended')
                        <span class="text-xs px-2 py-1 rounded-full bg-zinc-800 text-zinc-500">Selesai</span>
                    @endif
                </div>

                @auth
                    <form action="{{ route('watchlist.toggle', $auction) }}" method="POST">
                        @csrf
                        @php
                            $isWatched = auth()->user()->watchlists()->where('auction_id', $auction->id)->exists();
                        @endphp
                        <button type="submit" class="flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-xl border transition-all {{ $isWatched ? 'bg-amber-400 border-amber-400 text-zinc-950 shadow-[0_0_15px_rgba(251,191,36,0.3)]' : 'bg-zinc-900 border-zinc-700 text-zinc-400 hover:border-zinc-500 hover:text-white' }}">
                            <svg class="w-4 h-4 {{ $isWatched ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            {{ $isWatched ? 'Tersimpan' : 'Simpan' }}
                        </button>
                    </form>
                @endauth
            </div>

            <p class="text-zinc-400 mt-4 leading-relaxed">{{ $auction->description }}</p>

            {{-- Price Info --}}
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4 transition-all duration-500" :class="isPriceUpdated ? 'border-amber-400 shadow-[0_0_15px_rgba(251,191,36,0.1)]' : ''">
                    <p class="text-xs text-zinc-500">Harga Saat Ini</p>
                    <p class="text-2xl font-semibold text-amber-400 mt-1" x-text="formatCurrency(currentPrice)"></p>
                </div>
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
                    <p class="text-xs text-zinc-500">Min. Kenaikan Bid</p>
                    <p class="text-2xl font-semibold text-white mt-1">Rp {{ number_format($auction->min_bid_increment, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($auction->buy_now_price)
                {{-- Buy Now Price --}}
                <div class="mt-4 bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-amber-400 font-medium uppercase tracking-wider">⚡ Beli Sekarang</p>
                            <p class="text-2xl font-bold text-amber-400 mt-1">Rp {{ number_format($auction->buy_now_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-zinc-400">Bid dengan harga ini</p>
                            <p class="text-xs text-zinc-400">untuk menang langsung!</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Timer --}}
            <div x-data="countdown('{{ $auction->end_time }}')" class="mt-4">
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 relative overflow-hidden group">
                    {{-- Progress Bar (Optional Visual) --}}
                    <div class="absolute bottom-0 left-0 h-1 bg-amber-500/30 transition-all duration-1000" :style="'width: ' + progress + '%'"></div>
                    
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-[0.2em] font-black">Waktu Tersisa</p>
                            <div class="flex items-baseline gap-1 mt-1 font-mono">
                                <template x-if="days > 0">
                                    <div class="flex items-baseline">
                                        <span x-text="days" class="text-3xl font-bold text-white"></span>
                                        <span class="text-xs text-zinc-500 ml-1 mr-2 uppercase">Hari</span>
                                    </div>
                                </template>
                                <span x-text="formatNumber(hours)" class="text-3xl font-bold transition-colors duration-500" :class="isUrgent ? 'text-orange-500' : 'text-white'"></span>
                                <span class="text-xl font-bold text-zinc-700 mx-0.5">:</span>
                                <span x-text="formatNumber(minutes)" class="text-3xl font-bold transition-colors duration-500" :class="isUrgent ? 'text-orange-500' : 'text-white'"></span>
                                <span class="text-xl font-bold text-zinc-700 mx-0.5">:</span>
                                <span x-text="formatNumber(seconds)" class="text-3xl font-bold transition-colors duration-500" :class="isCritical ? 'text-red-500 animate-pulse' : (isUrgent ? 'text-orange-500' : 'text-amber-400')"></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-zinc-600 uppercase tracking-widest">Berakhir Pada</p>
                            <p class="text-xs text-zinc-400 font-medium mt-1">{{ \Carbon\Carbon::parse($auction->end_time)->format('H:i') }} WIB</p>
                            <p class="text-[10px] text-zinc-500">{{ \Carbon\Carbon::parse($auction->end_time)->format('d M Y') }}</p>
                        </div>
                    </div>

                    {{-- Urgent Status Alert --}}
                    <template x-if="isCritical && !isEnded">
                        <div class="mt-3 py-1 px-3 bg-red-500/10 border border-red-500/20 rounded-lg text-center">
                            <p class="text-[10px] text-red-500 font-bold uppercase tracking-widest animate-pulse">⚡ Segera Berakhir! Segera pasang penawaran!</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Bid Form --}}
            @if($auction->status === 'active')
                @auth
                    <div x-data="{ isEnded: false }" @countdown-ended.window="isEnded = true">
                        <form method="POST" action="{{ route('bids.store', $auction) }}" class="mt-6">
                            @csrf
                            <label class="block text-sm text-zinc-400 mb-2">Jumlah Bid Kamu</label>
                            <div class="flex gap-3">
                                <input
                                    type="number"
                                    name="amount"
                                    id="bid-amount"
                                    :disabled="isEnded"
                                    :min="currentPrice + minIncrement"
                                    :value="currentPrice + minIncrement"
                                    class="flex-1 bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                <button type="submit" 
                                    :disabled="isEnded"
                                    class="px-6 py-3 bg-amber-400 text-zinc-950 font-semibold rounded-xl hover:bg-amber-300 transition-all disabled:bg-zinc-800 disabled:text-zinc-600 disabled:cursor-not-allowed transform active:scale-95"
                                    :class="isEnded ? '' : 'shadow-[0_0_15px_rgba(245,158,11,0.3)] hover:shadow-amber-500/20'"
                                >
                                    <span x-show="!isEnded">Bid Sekarang</span>
                                    <span x-show="isEnded">Lelang Selesai</span>
                                </button>
                            </div>
                            @error('amount')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-zinc-600 text-xs text-zinc-500">Saldo kamu: <span class="font-bold">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span></p>
                                @if($auction->buy_now_price)
                                    <button type="button" 
                                        x-show="!isEnded"
                                        onclick="document.getElementById('bid-amount').value = {{ $auction->buy_now_price }}" 
                                        class="text-xs text-amber-500/80 hover:text-amber-400 transition-colors font-bold uppercase tracking-tighter"
                                    >
                                        ⚡ Set ke Buy Now Price
                                    </button>
                                @endif
                            </div>
                        </form>

                        {{-- Proxy Bid Form --}}
                        <div x-data="{ showProxy: false }" class="mt-8 pt-6 border-t border-zinc-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-white font-medium">Auto-Bid (Proxy Bidding)</h3>
                                    <p class="text-xs text-zinc-500">Biar sistem yang nge-bid otomatis buat kamu sampai batas tertentu.</p>
                                </div>
                                <button @click="showProxy = !showProxy" type="button" class="text-xs font-bold text-amber-400 hover:text-amber-300 uppercase tracking-widest">
                                    <span x-show="!showProxy">Atur Auto-Bid</span>
                                    <span x-show="showProxy">Tutup</span>
                                </button>
                            </div>

                            <div x-show="showProxy" x-collapse x-cloak class="mt-4 bg-zinc-900/50 border border-zinc-800 rounded-xl p-4">
                                @php
                                    $myProxy = $auction->proxyBids()->where('user_id', auth()->id())->first();
                                @endphp

                                @if($myProxy && $myProxy->is_active)
                                    <div class="flex items-center justify-between mb-4 p-3 bg-amber-400/10 border border-amber-400/20 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-2 w-2 rounded-full bg-amber-400"></span>
                                            <p class="text-xs text-amber-400 font-medium">Auto-Bid Aktif: <span class="font-bold">Rp {{ number_format($myProxy->max_amount, 0, ',', '.') }}</span></p>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('bids.proxy', $auction) }}" method="POST">
                                    @csrf
                                    <label class="block text-[10px] text-zinc-500 uppercase tracking-widest mb-2">Batas Maksimal Bid Kamu</label>
                                    <div class="flex gap-2">
                                        <input
                                            type="number"
                                            name="max_amount"
                                            :min="currentPrice + minIncrement"
                                            placeholder="Masukkan harga maksimal..."
                                            class="flex-1 bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-amber-400"
                                        >
                                        <button type="submit" class="px-4 py-2 bg-zinc-800 text-white text-xs font-bold rounded-lg hover:bg-zinc-700 transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                    <p class="mt-2 text-[10px] text-zinc-600 leading-tight italic">
                                        *Sistem akan otomatis nge-bid sedikit di atas penawar lain sampai mencapai harga maksimal ini.
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-4 bg-zinc-900 border border-zinc-800 rounded-xl text-center">
                        <p class="text-zinc-400 text-sm mb-3">Login untuk ikut lelang</p>
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-amber-400 text-zinc-950 font-semibold rounded-lg hover:bg-amber-300 transition-colors text-sm">Login</a>
                    </div>
                @endauth
            @endif

            {{-- Seller Info --}}
            <div class="mt-8 pt-6 border-t border-zinc-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-500 overflow-hidden border border-white/5">
                        @if($auction->seller->avatar)
                            <img src="{{ Storage::url($auction->seller->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Penjual</p>
                        <p class="text-sm font-bold text-white">{{ $auction->seller->name }}</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="flex items-center gap-1 justify-end">
                        @php $avgRating = $auction->seller->averageRating(); @endphp
                        @for($i=1; $i<=5; $i++)
                            <svg class="w-3 h-3 {{ $i <= $avgRating ? 'text-amber-400 fill-current' : 'text-zinc-700' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                        <span class="text-xs font-bold text-white ml-1">{{ number_format($avgRating, 1) }}</span>
                    </div>
                    <p class="text-[10px] text-zinc-500 uppercase tracking-tighter">{{ $auction->seller->reviews()->count() }} Penilaian</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bid History --}}
    <div class="mt-12">
        <h2 class="font-display text-2xl text-white mb-6">Riwayat Bid</h2>
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-zinc-800">
                    <tr>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Bidder</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Jumlah</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Status</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800" id="bid-history-list">
                    <template x-for="(bid, index) in bids" :key="index">
                        <tr :class="index === 0 ? 'bg-amber-400/5' : ''" class="animate-in fade-in slide-in-from-left-2 duration-500">
                            <td class="px-6 py-4 text-zinc-300">
                                <span x-show="index === 0">🏆 </span><span x-text="bid.user_name"></span>
                            </td>
                            <td class="px-6 py-4 text-amber-400 font-semibold" x-text="formatCurrency(bid.amount)"></td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-500" x-text="bid.created_at"></td>
                        </tr>
                    </template>
                    @foreach($auction->bids->where('status', 'approved')->sortByDesc('amount') as $bid)
                        <tr x-show="bids.length === 0" class="{{ $loop->first ? 'bg-amber-400/5' : '' }}">
                            <td class="px-6 py-4 text-zinc-300">
                                {{ $loop->first ? '🏆 ' : '' }}{{ $bid->user->name }}
                            </td>
                            <td class="px-6 py-4 text-amber-400 font-semibold">Rp {{ number_format($bid->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-500">{{ $bid->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div x-show="bids.length === 0 && {{ $auction->bids->where('status', 'approved')->count() }} === 0" class="p-6 text-center">
                <p class="text-zinc-600">Belum ada bid. Jadilah yang pertama!</p>
            </div>
        </div>
    </div>
    {{-- Reviews Section --}}
    <div class="mt-12 border-t border-white/5 pt-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="font-display text-2xl text-white">Penilaian Pembeli</h2>
                <p class="text-xs text-zinc-500 mt-1 uppercase tracking-widest font-medium">Ulasan terbaru untuk penjual ini</p>
            </div>
            <div class="flex items-center gap-4 bg-zinc-900 border border-zinc-800 px-5 py-3 rounded-2xl">
                <div class="flex text-amber-400">
                    @for($i=1; $i<=5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $auction->seller->averageRating() ? 'fill-current' : 'opacity-20' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    @endfor
                </div>
                <span class="text-sm font-bold text-white">{{ number_format($auction->seller->averageRating(), 1) }} / 5.0</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($auction->seller->reviews()->latest()->take(6)->get() as $review)
                <div class="glass-card p-6 rounded-3xl border border-white/5 flex flex-col gap-4 hover:border-white/10 transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-zinc-800 flex items-center justify-center text-[10px] font-black text-zinc-500 border border-white/5">
                                {{ strtoupper(substr($review->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ $review->user->name }}</p>
                                <p class="text-[10px] text-zinc-500 uppercase tracking-tighter">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-current' : 'text-zinc-700' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <div class="relative">
                        <svg class="absolute -top-1 -left-1 w-4 h-4 text-white/5 fill-current" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C20.1216 16 21.017 16.8954 21.017 18V21C21.017 22.1046 20.1216 23 19.017 23H16.017C14.9124 23 14.017 22.1046 14.017 21ZM14.017 11V8C14.017 6.89543 14.9124 6 16.017 6H19.017C20.1216 6 21.017 6.89543 21.017 8V11C21.017 12.1046 20.1216 13 19.017 13H16.017C14.9124 13 14.017 12.1046 14.017 11ZM3.017 21V18C3.017 16.8954 3.91243 16 5.017 16H8.017C9.12157 16 10.017 16.8954 10.017 18V21C10.017 22.1046 9.12157 23 8.017 23H5.017C3.91243 23 3.017 22.1046 3.017 21ZM3.017 11V8C3.017 6.89543 3.91243 6 5.017 6H8.017C9.12157 6 10.017 6.89543 10.017 8V11C10.017 12.1046 9.12157 13 8.017 13H5.017C3.91243 13 3.017 12.1046 3.017 11Z"/></svg>
                        <p class="text-sm text-zinc-400 italic leading-relaxed pl-4">"{{ $review->comment ?: 'Tidak ada komentar.' }}"</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center glass-card rounded-3xl border border-dashed border-white/10 opacity-50">
                    <p class="text-zinc-600 text-sm italic">Belum ada penilaian untuk penjual ini. Jadilah pembeli pertama yang memberikan ulasan!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function auctionRoom(auctionId, initialPrice, minIncrement) {
        return {
            currentPrice: initialPrice,
            minIncrement: minIncrement,
            bids: [],
            isPriceUpdated: false,

            init() {
                window.Echo.channel(`auctions.${auctionId}`)
                    .listen('BidPlaced', (e) => {
                        this.currentPrice = e.current_price;
                        this.bids.unshift({
                            amount: e.amount,
                            user_name: e.user_name,
                            created_at: e.created_at
                        });
                        
                        // Flash effect
                        this.isPriceUpdated = true;
                        setTimeout(() => this.isPriceUpdated = false, 2000);

                        // Toast Notification
                        if (typeof window.showToast === 'function') {
                            window.showToast(`Bid baru masuk: Rp ${e.amount.toLocaleString('id-ID')}`);
                        }
                    });
            },

            formatCurrency(val) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(val).replace('IDR', 'Rp');
            }
        }
    }

    function countdown(endTime) {
        return {
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            isUrgent: false,
            isCritical: false,
            isEnded: false,
            progress: 100,
            interval: null,

            init() {
                this.updateCountdown();
                this.interval = setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },

            updateCountdown() {
                const end = new Date(endTime).getTime();
                const now = new Date().getTime();
                const distance = end - now;

                if (distance < 0) {
                    if (!this.isEnded) {
                        this.isEnded = true;
                        window.dispatchEvent(new CustomEvent('countdown-ended'));
                    }
                    this.days = 0;
                    this.hours = 0;
                    this.minutes = 0;
                    this.seconds = 0;
                    this.progress = 0;
                    clearInterval(this.interval);
                    return;
                }

                this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Urgency Checks
                this.isUrgent = distance < (60 * 60 * 1000); // < 1 hour
                this.isCritical = distance < (5 * 60 * 1000); // < 5 mins
                
                // Simple progress estimation
                this.progress = Math.max(0, (distance / (24 * 60 * 60 * 1000)) * 100); 
            },

            formatNumber(n) {
                return n.toString().padStart(2, '0');
            }
        }
    }
</script>
@endsection
