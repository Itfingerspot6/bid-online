@extends('layouts.app')

@section('title', $auction->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
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
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
                    <p class="text-xs text-zinc-500">Harga Saat Ini</p>
                    <p class="text-2xl font-semibold text-amber-400 mt-1">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
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
            <div class="mt-4 bg-zinc-900 border border-zinc-800 rounded-xl p-4">
                <p class="text-xs text-zinc-500">Berakhir</p>
                <p class="text-white font-medium mt-1">{{ \Carbon\Carbon::parse($auction->end_time)->format('d M Y, H:i') }} WIB</p>
                <p class="text-zinc-400 text-sm mt-1">{{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</p>
            </div>

            

            {{-- Bid Form --}}
            @if($auction->status === 'active')
                @auth
                    <form method="POST" action="{{ route('bids.store', $auction) }}" class="mt-6">
                        @csrf
                        <label class="block text-sm text-zinc-400 mb-2">Jumlah Bid Kamu</label>
                        <div class="flex gap-3">
                            <input
                                type="number"
                                name="amount"
                                id="bid-amount"
                                min="{{ $auction->current_price + $auction->min_bid_increment }}"
                                value="{{ $auction->current_price + $auction->min_bid_increment }}"
                                class="flex-1 bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors"
                            >
                            <button type="submit" class="px-6 py-3 bg-amber-400 text-zinc-950 font-semibold rounded-xl hover:bg-amber-300 transition-colors">
                                Bid Sekarang
                            </button>
                        </div>
                        @error('amount')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-zinc-600 text-xs">Saldo kamu: Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
                            @if($auction->buy_now_price)
                                <button type="button" onclick="document.getElementById('bid-amount').value = {{ $auction->buy_now_price }}" class="text-xs text-amber-400 hover:text-amber-300 transition-colors">
                                    ⚡ Set ke Buy Now Price
                                </button>
                            @endif
                        </div>
                    </form>
                @else
                    <div class="mt-6 p-4 bg-zinc-900 border border-zinc-800 rounded-xl text-center">
                        <p class="text-zinc-400 text-sm mb-3">Login untuk ikut lelang</p>
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-amber-400 text-zinc-950 font-semibold rounded-lg hover:bg-amber-300 transition-colors text-sm">Login</a>
                    </div>
                @endauth
            @endif

            {{-- Seller --}}
            <div class="mt-6 flex items-center gap-3 text-sm text-zinc-500">
                <span>Dijual oleh</span>
                <span class="text-zinc-300 font-medium">{{ $auction->seller->name }}</span>
            </div>
        </div>
    </div>

    {{-- Bid History --}}
    <div class="mt-12">
        <h2 class="font-display text-2xl text-white mb-6">Riwayat Bid</h2>
        @if($auction->bids->count() > 0)
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
                    <tbody class="divide-y divide-zinc-800">
                        @foreach($auction->bids->sortByDesc('amount') as $bid)
                            <tr class="{{ $loop->first && $bid->status === 'approved' ? 'bg-amber-400/5' : '' }}">
                                <td class="px-6 py-4 text-zinc-300">
                                    {{ $loop->first && $bid->status === 'approved' ? '🏆 ' : '' }}{{ $bid->user->name }}
                                </td>
                                <td class="px-6 py-4 text-amber-400 font-semibold">Rp {{ number_format($bid->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @if($bid->status === 'pending')
                                        <span class="text-xs px-2 py-1 rounded-full bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Pending</span>
                                    @elseif($bid->status === 'approved')
                                        <span class="text-xs px-2 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-zinc-500">{{ $bid->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-zinc-600">Belum ada bid. Jadilah yang pertama!</p>
        @endif
    </div>
</div>
@endsection