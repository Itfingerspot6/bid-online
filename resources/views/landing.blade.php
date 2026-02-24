@extends('layouts.app')

@section('title', 'Platform Lelang Online Terpercaya')

@section('content')

{{-- Hero --}}
<div class="relative border-b border-zinc-800 py-24 overflow-hidden" style="background-color: #F5F0E8;">
    
    {{-- Hinterrhein geometric pattern --}}
    <div class="absolute inset-0 overflow-hidden opacity-60">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <!-- Lingkaran besar salmon kanan atas -->
            <circle cx="85%" cy="15%" r="80" fill="none" stroke="#E8836A" stroke-width="18"/>
            <!-- C shape navy -->
            <path d="M 900 80 A 60 60 0 1 0 900 200" fill="none" stroke="#1B2A4A" stroke-width="18" stroke-linecap="round"/>
            <!-- U shape green -->
            <path d="M 750 40 L 750 130 A 40 40 0 0 0 830 130 L 830 40" fill="none" stroke="#5BAD8F" stroke-width="16" stroke-linecap="round"/>
            <!-- Kotak kecil navy -->
            <rect x="640" y="60" width="28" height="28" fill="#1B2A4A"/>
            <!-- Lingkaran salmon bawah kiri -->
            <circle cx="12%" cy="80%" r="100" fill="none" stroke="#E8836A" stroke-width="20"/>
            <!-- C shape besar bawah -->
            <path d="M 200 350 A 80 80 0 1 0 200 510" fill="none" stroke="#5BAD8F" stroke-width="20" stroke-linecap="round"/>
            <!-- U shape navy bawah kanan -->
            <path d="M 1100 320 L 1100 430 A 55 55 0 0 0 1210 430 L 1210 320" fill="none" stroke="#1B2A4A" stroke-width="18" stroke-linecap="round"/>
            <!-- Lingkaran kecil filled salmon -->
            <circle cx="75%" cy="75%" r="45" fill="#E8836A"/>
            <!-- Lingkaran kecil outline navy -->
            <circle cx="30%" cy="20%" r="35" fill="none" stroke="#1B2A4A" stroke-width="12"/>
            <!-- Shape huruf e/c kanan bawah -->
            <path d="M 1150 450 A 70 70 0 1 0 1150 590 M 1080 520 L 1220 520" fill="none" stroke="#5BAD8F" stroke-width="16" stroke-linecap="round"/>
            <!-- Garis horizontal navy -->
            <rect x="60" y="250" width="120" height="18" rx="9" fill="#1B2A4A"/>
            <!-- Kotak kecil sage -->
            <rect x="500" y="350" width="22" height="22" fill="#8FAF7A"/>
            <!-- C shape salmon kecil -->
            <path d="M 450 180 A 40 40 0 1 0 450 260" fill="none" stroke="#E8836A" stroke-width="14" stroke-linecap="round"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-2xl">
            <span class="text-[#1B2A4A] text-sm font-medium uppercase tracking-widest">Platform Lelang Online</span>
            <h1 class="font-display text-6xl mt-4 leading-tight" style="color: #1B2A4A;">
                Lelang Barang <br><span style="color: #E8836A;">Terbaik</span> dengan Mudah
            </h1>
            <p class="text-lg mt-6 leading-relaxed" style="color: #4A5568;">
                BidOnline adalah platform lelang online terpercaya di Indonesia. Jual dan beli barang dengan sistem lelang yang transparan, aman, dan mudah digunakan.
            </p>
            <div class="flex gap-4 mt-8">
                <a href="{{ route('auctions.index') }}" class="px-8 py-3.5 font-semibold rounded-xl hover:opacity-90 transition-opacity" style="background-color: #1B2A4A; color: #F5F0E8;">
                    Lihat Lelang
                </a>
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-xl border-2 font-semibold hover:opacity-80 transition-opacity" style="border-color: #1B2A4A; color: #1B2A4A;">
                        Daftar Gratis
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="border-b border-zinc-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-8 text-center">
            <div>
                <p class="font-display text-4xl text-amber-400">1000+</p>
                <p class="text-zinc-500 text-sm mt-1">Lelang Selesai</p>
            </div>
            <div>
                <p class="font-display text-4xl text-amber-400">500+</p>
                <p class="text-zinc-500 text-sm mt-1">Pengguna Aktif</p>
            </div>
            <div>
                <p class="font-display text-4xl text-amber-400">99%</p>
                <p class="text-zinc-500 text-sm mt-1">Transaksi Sukses</p>
            </div>
        </div>
    </div>
</div>

{{-- Cara Kerja --}}
<div class="border-b border-zinc-800 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl text-white">Cara Kerja</h2>
            <p class="text-zinc-400 mt-3">Mudah, cepat, dan transparan</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach([
                ['01', 'Daftar Akun', 'Buat akun gratis dan verifikasi identitasmu'],
                ['02', 'Deposit Saldo', 'Top up saldo untuk mulai ikut lelang'],
                ['03', 'Ikut Lelang', 'Pilih barang dan pasang bid terbaikmu'],
                ['04', 'Menangkan', 'Bid tertinggi saat waktu habis jadi pemenang'],
            ] as $step)
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-amber-400/10 border border-amber-400/30 flex items-center justify-center mx-auto mb-4">
                        <span class="text-amber-400 font-semibold text-sm">{{ $step[0] }}</span>
                    </div>
                    <h3 class="text-white font-medium mb-2">{{ $step[1] }}</h3>
                    <p class="text-zinc-500 text-sm">{{ $step[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Lelang Terbaru --}}
<div class="border-b border-zinc-800 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <h2 class="font-display text-4xl text-white">Lelang Terbaru</h2>
            <a href="{{ route('auctions.index') }}" class="text-amber-400 text-sm hover:text-amber-300 transition-colors">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestAuctions as $auction)
                <a href="{{ route('auctions.show', $auction->slug) }}" class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all hover:-translate-y-1">
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
                    <div class="p-4">
                        <h3 class="text-white font-medium line-clamp-1 group-hover:text-amber-400 transition-colors">{{ $auction->title }}</h3>
                        <p class="text-amber-400 font-semibold mt-1">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                        <p class="text-zinc-500 text-xs mt-1">{{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- About --}}
<div class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="font-display text-4xl text-white mb-6">Tentang BidOnline</h2>
                <p class="text-zinc-400 leading-relaxed mb-4">
                    BidOnline adalah platform lelang online yang didirikan dengan misi untuk memberikan pengalaman lelang yang transparan, aman, dan menyenangkan bagi semua orang.
                </p>
                <p class="text-zinc-400 leading-relaxed mb-4">
                    Kami percaya bahwa setiap barang memiliki nilai yang tepat, dan sistem lelang adalah cara terbaik untuk menemukannya. Dengan teknologi modern dan sistem keamanan berlapis, setiap transaksi di BidOnline dijamin aman.
                </p>
                <div class="flex gap-4 mt-8">
                    @guest
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-amber-400 text-zinc-950 font-semibold rounded-xl hover:bg-amber-300 transition-colors">
                            Bergabung Sekarang
                        </a>
                    @endguest
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['🔒', 'Aman & Terpercaya', 'Sistem keamanan berlapis untuk setiap transaksi'],
                    ['⚡', 'Realtime', 'Update harga bid secara realtime'],
                    ['💰', 'Harga Terbaik', 'Dapatkan barang dengan harga terbaik'],
                    ['🤝', 'Komunitas', 'Bergabung dengan ribuan pengguna aktif'],
                ] as $feature)
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <span class="text-2xl">{{ $feature[0] }}</span>
                        <h3 class="text-white font-medium mt-2 mb-1">{{ $feature[1] }}</h3>
                        <p class="text-zinc-500 text-sm">{{ $feature[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection