@extends('layouts.app')

@section('title', 'Platform Lelang Online Terpercaya')

@section('content')

{{-- Hero --}}
<div class="relative py-20 lg:py-32 overflow-hidden bg-zinc-950">
    {{-- Animated Background Blobs --}}
    <div class="absolute top-0 -left-4 w-72 h-72 bg-amber-400 opacity-10 filter blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-0 -right-4 w-96 h-96 bg-orange-600 opacity-10 filter blur-[150px] animate-pulse" style="animation-delay: 2s;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/20 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Platform Lelang Online Terpercaya</span>
                </div>
                
                <h1 class="font-display text-5xl lg:text-7xl text-white leading-[1.1] mb-6 tracking-tight">
                    Lelang Barang <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Terbaik</span> 
                    dengan Mudah
                </h1>
                
                <p class="text-zinc-400 text-lg lg:text-xl leading-relaxed mb-10 max-w-xl">
                    BidOnline adalah platform lelang online terpercaya di Indonesia. Jual dan beli barang dengan sistem lelang yang transparan, aman, dan mudah digunakan.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('auctions.index') }}" class="btn-primary flex items-center gap-2 group">
                        Lihat Lelang Aktif
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn-outline">
                            Mulai Sekarang — Gratis
                        </a>
                    @endguest
                </div>

                <div class="mt-12 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        @foreach(range(1, 4) as $i)
                            <div class="w-10 h-10 rounded-full border-2 border-zinc-950 bg-zinc-800 flex items-center justify-center overflow-hidden">
                                <img src="https://i.pravatar.cc/100?u={{ $i }}" alt="User">
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-zinc-500">
                        Bergabung dengan <span class="text-white font-bold">500+</span> pengguna aktif lainnya
                    </p>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="glass-card p-4 rounded-[2.5rem] relative overflow-hidden animate-float">
                    <div class="bg-zinc-800 aspect-[4/5] rounded-[2rem] overflow-hidden relative group">
                        <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent"></div>
                        
                        <div class="absolute bottom-6 left-6 right-6 p-6 glass-card rounded-2xl backdrop-blur-2xl">
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <p class="text-[10px] text-amber-400 uppercase font-black tracking-widest mb-1">Bid Tertinggi</p>
                                    <p class="text-2xl font-bold text-white">Rp 2.500.000</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest mb-1">Berakhir dalam</p>
                                    <p class="text-lg font-bold text-white">02:45:12</p>
                                </div>
                            </div>
                            <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="w-2/3 h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Decorative Elements --}}
                <div class="absolute -top-6 -right-6 w-32 h-32 glass-card rounded-3xl flex items-center justify-center animate-bounce shadow-2xl" style="animation-duration: 3s;">
                    <span class="text-4xl text-amber-400">🏆</span>
                </div>
                <div class="absolute -bottom-6 -left-6 px-6 py-4 glass-card rounded-2xl shadow-2xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-zinc-500 uppercase font-black">Transaksi Aman</p>
                        <p class="text-xs text-white font-bold">Terverifikasi 100%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="border-y border-white/5 bg-zinc-950 py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div class="group">
                <p class="font-display text-6xl text-white mb-2 group-hover:text-amber-400 transition-colors duration-500">1000+</p>
                <p class="text-zinc-500 text-xs uppercase tracking-[0.2em] font-black">Lelang Selesai</p>
                <div class="w-12 h-1 bg-gradient-to-r from-amber-400 to-transparent mx-auto mt-6 rounded-full opacity-30"></div>
            </div>
            <div class="group">
                <p class="font-display text-6xl text-white mb-2 group-hover:text-amber-400 transition-colors duration-500">500+</p>
                <p class="text-zinc-500 text-xs uppercase tracking-[0.2em] font-black">Pengguna Aktif</p>
                <div class="w-12 h-1 bg-gradient-to-r from-amber-400 to-transparent mx-auto mt-6 rounded-full opacity-30"></div>
            </div>
            <div class="group">
                <p class="font-display text-6xl text-white mb-2 group-hover:text-amber-400 transition-colors duration-500">99%</p>
                <p class="text-zinc-500 text-xs uppercase tracking-[0.2em] font-black">Transaksi Sukses</p>
                <div class="w-12 h-1 bg-gradient-to-r from-amber-400 to-transparent mx-auto mt-6 rounded-full opacity-30"></div>
            </div>
        </div>
    </div>
</div>

{{-- Cara Kerja --}}
<div class="py-32 bg-zinc-950 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="font-display text-5xl text-white mb-4 tracking-tight">Cara Kerja</h2>
            <div class="h-1.5 w-24 bg-gradient-to-r from-amber-400 to-orange-500 mx-auto rounded-full mb-6"></div>
            <p class="text-zinc-500 text-lg">Proses lelang yang transparan dan aman dalam 4 langkah mudah</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
                ['01', 'Daftar Akun', 'Buat akun gratis dan verifikasi identitasmu untuk mulai bertransaksi.', '👤'],
                ['02', 'Deposit Saldo', 'Top up saldo kamu dengan berbagai pilihan metode pembayaran aman.', '💳'],
                ['03', 'Ikut Lelang', 'Pilih barang impianmu dan pasang penawaran bid terbaikmu.', '⚡'],
                ['04', 'Menangkan', 'Bid tertinggi saat waktu lelang habis akan langsung jadi pemenang!', '🏆'],
            ] as $step)
                <div class="glass-card glass-card-hover p-8 rounded-3xl relative group">
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-500">
                        {{ $step[3] }}
                    </div>
                    <div class="absolute top-8 right-8 text-4xl font-black text-white/5">{{ $step[0] }}</div>
                    <h3 class="text-xl font-bold text-white mb-4 group-hover:text-amber-400 transition-colors">{{ $step[1] }}</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">{{ $step[2] }}</p>
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
                    <div class="p-4" x-data="auctionTimer('{{ $auction->end_time }}')">
                        <h3 class="text-white font-medium line-clamp-1 group-hover:text-amber-400 transition-colors">{{ $auction->title }}</h3>
                        
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <p class="text-[10px] text-zinc-500 uppercase font-black">Harga</p>
                                <p class="text-amber-400 font-bold">Rp {{ number_format($auction->current_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-zinc-500 uppercase font-black">Berakhir</p>
                                <p class="text-xs font-mono font-bold transition-colors" :class="isUrgent ? 'text-orange-500' : 'text-zinc-300'">
                                    <span x-text="timeLeft"></span>
                                </p>
                            </div>
                        </div>

                        {{-- Progress Bar Small --}}
                        <div class="w-full h-1 bg-white/5 rounded-full mt-3 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-1000" :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
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