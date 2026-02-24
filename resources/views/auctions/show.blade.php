@extends('layouts.app')

@section('title', $auction->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        {{-- Images --}}
        <div>
            @if($auction->images && count($auction->images) > 0)
                <div class="aspect-square rounded-xl overflow-hidden" style="border: 2px solid #EDE7D9;">
                    <img src="{{ Storage::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover">
                </div>
                @if(count($auction->images) > 1)
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach(array_slice($auction->images, 1) as $image)
                            <div class="aspect-square rounded-lg overflow-hidden" style="border: 2px solid #EDE7D9;">
                                <img src="{{ Storage::url($image) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="aspect-square rounded-xl flex items-center justify-center" style="background-color: #EDE7D9; border: 2px solid #D5CEBC;">
                    <svg class="w-20 h-20" fill="none" stroke="#6B7280" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Detail --}}
        <div>
            <span class="text-xs uppercase tracking-widest font-medium" style="color: #5BAD8F;">{{ $auction->category->name }}</span>
            <h1 class="font-display text-3xl mt-2" style="color: #1B2A4A;">{{ $auction->title }}</h1>

            <div class="mt-2 flex items-center gap-2">
                @if($auction->status === 'active')
                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full" style="background-color: rgba(91,173,143,0.1); color: #5BAD8F; border: 1px solid rgba(91,173,143,0.3);">
                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: #5BAD8F;"></span>
                        Aktif
                    </span>
                @elseif($auction->status === 'ended')
                    <span class="text-xs px-2 py-1 rounded-full" style="background-color: #EDE7D9; color: #6B7280;">Selesai</span>
                @endif
            </div>

            <p class="mt-4 leading-relaxed" style="color: #6B7280;">{{ $auction->description }}</p>

            {{-- Price Info --}}
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="rounded-xl p-4" style="background-color: white; border: 2px solid #EDE7D9;">
                    <p class="text-xs" style="color: #6B7280;">Harga Saat Ini</p>
                    <p class="text-2xl font-semibold mt-1" style="color: #E8836A;">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl p-4" style="background-color: white; border: 2px solid #EDE7D9;">
                    <p class="text-xs" style="color: #6B7280;">Min. Kenaikan Bid</p>
                    <p class="text-2xl font-semibold mt-1" style="color: #1B2A4A;">Rp {{ number_format($auction->min_bid_increment, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Buy Now Price --}}
            @if($auction->buy_now_price)
                <div class="mt-4 p-4 rounded-xl" style="background-color: rgba(91,173,143,0.08); border: 2px solid rgba(91,173,143,0.3);">
                    <p class="text-xs font-medium mb-1" style="color: #5BAD8F;">⚡ Harga Batas (Buy Now)</p>
                    <p class="text-xl font-semibold" style="color: #5BAD8F;">Rp {{ number_format($auction->buy_now_price, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color: #6B7280;">Bid sejumlah ini untuk langsung memenangkan lelang!</p>
                </div>
            @endif

            {{-- Timer --}}
            <div class="mt-4 rounded-xl p-4" style="background-color: white; border: 2px solid #EDE7D9;">
                <p class="text-xs" style="color: #6B7280;">Berakhir</p>
                <p class="font-medium mt-1" style="color: #1B2A4A;">{{ \Carbon\Carbon::parse($auction->end_time)->format('d M Y, H:i') }} WIB</p>
                <p class="text-sm mt-1" style="color: #6B7280;">{{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</p>
            </div>

            {{-- Bid Form --}}
            @if($auction->status === 'active')
                @auth
                    <form method="POST" action="{{ route('bids.store', $auction) }}" class="mt-6">
                        @csrf
                        <label class="block text-sm mb-2" style="color: #6B7280;">Jumlah Bid Kamu</label>
                        <div class="flex gap-3">
                            <input
                                type="number"
                                name="amount"
                                min="{{ $auction->current_price + $auction->min_bid_increment }}"
                                max="{{ $auction->buy_now_price ?? '' }}"
                                value="{{ $auction->current_price + $auction->min_bid_increment }}"
                                class="flex-1 rounded-xl px-4 py-3 focus:outline-none transition-colors"
                                style="background-color: #F5F0E8; border: 2px solid #EDE7D9; color: #1B2A4A;"
                            >
                            <button type="submit" class="px-6 py-3 font-semibold rounded-xl transition-opacity hover:opacity-90" style="background-color: #1B2A4A; color: #F5F0E8;">
                                Bid Sekarang
                            </button>
                        </div>
                        @error('amount')
                            <p class="text-sm mt-2" style="color: #E8836A;">{{ $message }}</p>
                        @enderror
                        <p class="text-xs mt-2" style="color: #6B7280;">Saldo kamu: Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
                    </form>
                @else
                    <div class="mt-6 p-4 rounded-xl text-center" style="background-color: white; border: 2px solid #EDE7D9;">
                        <p class="text-sm mb-3" style="color: #6B7280;">Login untuk ikut lelang</p>
                        <a href="{{ route('login') }}" class="px-6 py-2 font-semibold rounded-lg transition-opacity hover:opacity-90 text-sm" style="background-color: #1B2A4A; color: #F5F0E8;">Login</a>
                    </div>
                @endauth
            @elseif($auction->status === 'ended')
                <div class="mt-6 p-4 rounded-xl text-center" style="background-color: rgba(91,173,143,0.08); border: 2px solid rgba(91,173,143,0.3);">
                    @if($auction->winner)
                        <p class="font-semibold" style="color: #5BAD8F;">🏆 Lelang Selesai</p>
                        <p class="text-sm mt-1" style="color: #6B7280;">Dimenangkan oleh <span class="font-medium" style="color: #1B2A4A;">{{ $auction->winner->name }}</span></p>
                    @else
                        <p class="font-semibold" style="color: #6B7280;">Lelang Selesai</p>
                        <p class="text-sm mt-1" style="color: #6B7280;">Tidak ada pemenang</p>
                    @endif
                </div>
            @endif

            {{-- Seller --}}
            <div class="mt-6 flex items-center gap-3 text-sm" style="color: #6B7280;">
                <span>Dijual oleh</span>
                <span class="font-medium" style="color: #1B2A4A;">{{ $auction->seller->name }}</span>
            </div>
        </div>
    </div>

    {{-- Bid History --}}
    <div class="mt-12">
        <h2 class="font-display text-2xl mb-6" style="color: #1B2A4A;">Riwayat Bid</h2>
        @if($auction->bids->count() > 0)
            <div class="rounded-xl overflow-hidden" style="background-color: white; border: 2px solid #EDE7D9;">
                <table class="w-full text-sm">
                    <thead style="border-bottom: 2px solid #EDE7D9;">
                        <tr>
                            <th class="text-left px-6 py-4 font-medium" style="color: #6B7280;">Bidder</th>
                            <th class="text-left px-6 py-4 font-medium" style="color: #6B7280;">Jumlah</th>
                            <th class="text-left px-6 py-4 font-medium" style="color: #6B7280;">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auction->bids->sortByDesc('amount') as $bid)
                            <tr style="{{ $loop->first ? 'background-color: rgba(232,131,106,0.05);' : '' }} border-bottom: 1px solid #EDE7D9;">
                                <td class="px-6 py-4" style="color: #1B2A4A;">
                                    {{ $loop->first ? '🏆 ' : '' }}{{ $bid->user->name }}
                                </td>
                                <td class="px-6 py-4 font-semibold" style="color: #E8836A;">Rp {{ number_format($bid->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4" style="color: #6B7280;">{{ $bid->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color: #6B7280;">Belum ada bid. Jadilah yang pertama!</p>
        @endif
    </div>
</div>
@endsection