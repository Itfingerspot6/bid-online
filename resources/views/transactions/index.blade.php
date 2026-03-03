@extends('layouts.app')

@section('title', 'Dompet & Transaksi')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="font-display text-5xl text-white mb-3 tracking-tight">Dompet <span class="text-amber-400">&</span> Transaksi</h1>
            <p class="text-zinc-500 text-lg">Kelola saldo dan pantau seluruh aktivitas keuanganmu.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-xs font-bold text-zinc-300 uppercase tracking-widest">Akun Terverifikasi</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column: Wallet Card & Quick Actions --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Premium Wallet Card --}}
            <div class="glass-card stat-card-gradient-1 p-8 rounded-[2.5rem] relative overflow-hidden group border-amber-400/20 shadow-[0_20px_50px_rgba(251,191,36,0.1)]">
                {{-- Decorative Circles --}}
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-amber-400/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-8 bg-black/20 rounded-md backdrop-blur-md border border-white/5 flex items-center justify-center overflow-hidden">
                            <div class="w-8 h-6 bg-amber-400/20 rounded flex items-center justify-center">
                                <span class="text-amber-400 text-[10px]">GOLD</span>
                            </div>
                        </div>
                        <svg class="w-8 h-8 text-amber-400/50" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4V6h16v12zm-10-7h8v2h-8z"/></svg>
                    </div>
                    
                    <p class="text-[10px] text-zinc-400 uppercase font-black tracking-[0.2em] mb-2">Total Saldo Kamu</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-xl font-bold text-amber-400">Rp</span>
                        <h2 class="text-4xl font-display text-white tracking-tight">{{ number_format(auth()->user()->balance, 0, ',', '.') }}</h2>
                    </div>

                    <div class="mt-12 flex justify-between items-end">
                        <div class="space-y-1">
                            <p class="text-[8px] text-zinc-500 uppercase font-black tracking-widest leading-none">Pemilik Akun</p>
                            <p class="text-sm font-bold text-white uppercase">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-[8px] text-zinc-500 uppercase font-black tracking-widest leading-none">Status</p>
                             <p class="text-xs font-bold text-green-400 uppercase">Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass-card p-6 rounded-[2rem] space-y-3">
                <button onclick="document.getElementById('deposit-modal').classList.remove('hidden')" class="btn-primary w-full flex items-center justify-center gap-3 py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Saldo (Deposit)
                </button>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest">Butuh Bantuan?</p>
                        <a href="#" class="text-xs text-amber-400 font-bold hover:text-amber-300 transition-colors">Hubungi Support</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Transaction History --}}
        <div class="lg:col-span-2">
            <div class="glass-card rounded-[2.5rem] overflow-hidden min-h-[600px] flex flex-col">
                <div class="p-8 border-b border-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Riwayat Aktivitas</h3>
                        <p class="text-xs text-zinc-500 mt-1">Daftar transaksi terbaru di akun Anda</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center text-zinc-500 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </span>
                    </div>
                </div>

                <div class="flex-1 p-6">
                    @if($transactions->count() > 0)
                        <div class="space-y-4">
                            @foreach($transactions as $transaction)
                                <div class="group flex items-center gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-white/10 hover:bg-white/[0.04] transition-all">
                                    {{-- Ikon Tipe --}}
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110
                                        {{ $transaction->type === 'deposit' ? 'bg-green-500/10 text-green-400' : '' }}
                                        {{ $transaction->type === 'withdrawal' ? 'bg-red-500/10 text-red-400' : '' }}
                                        {{ $transaction->type === 'bid' ? 'bg-amber-500/10 text-amber-400' : '' }}
                                        {{ $transaction->type === 'refund' ? 'bg-purple-500/10 text-purple-400' : '' }}
                                    ">
                                        @if($transaction->type === 'deposit')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                        @elseif($transaction->type === 'bid')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        @elseif($transaction->type === 'refund')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        @endif
                                    </div>

                                    {{-- Detail --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm font-bold text-white truncate">{{ $transaction->description ?? 'Tanpa Keterangan' }}</p>
                                            <p class="text-sm font-bold
                                                {{ in_array($transaction->type, ['deposit', 'refund']) ? 'text-green-400' : 'text-red-400' }}
                                            ">
                                                {{ in_array($transaction->type, ['deposit', 'refund']) ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="text-[10px] text-zinc-500 font-medium">{{ $transaction->created_at->format('d M Y • H:i') }}</span>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border
                                                    {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20' : '' }}
                                                    {{ $transaction->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : '' }}
                                                    {{ $transaction->status === 'failed' ? 'bg-red-500/10 text-red-400 border-red-500/20' : '' }}
                                                ">
                                                    {{ $transaction->status }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] text-zinc-600 font-mono hidden sm:inline">{{ $transaction->payment_ref }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-20 h-20 bg-zinc-900 border border-zinc-800 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h4 class="text-xl font-bold text-white mb-2">Belum ada transaksi</h4>
                            <p class="text-zinc-500 max-w-xs mx-auto text-sm">Coba lakukan deposit pertama kamu untuk mulai mengikuti lelang yang tersedia.</p>
                        </div>
                    @endif
                </div>

                {{-- Pagination Custom --}}
                @if($transactions->hasPages())
                    <div class="p-8 border-t border-white/5 bg-black/20">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Deposit Modal Premium --}}
<div id="deposit-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div onclick="document.getElementById('deposit-modal').classList.add('hidden')" class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-md" aria-hidden="true"></div>

        {{-- Modal panel --}}
        <div class="inline-block align-bottom glass-card p-2 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border-white/10">
            <div class="relative bg-zinc-900/90 rounded-[2.2rem] p-8">
                {{-- Close Button --}}
                <button onclick="document.getElementById('deposit-modal').classList.add('hidden')" class="absolute top-6 right-6 text-zinc-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-amber-400/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-amber-400/20">
                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-2xl font-display text-white" id="modal-title">Deposit Saldo</h3>
                    <p class="text-sm text-zinc-500 mt-2">Isi saldo untuk mulai melakukan penawaran</p>
                </div>

                <form method="POST" action="{{ route('transactions.deposit') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] text-zinc-500 uppercase font-black tracking-widest mb-3">Jumlah Deposit (Min Rp 10.000)</label>
                        <div class="relative group">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-zinc-400 font-bold group-focus-within:text-amber-400 transition-colors">Rp</span>
                            <input type="number" name="amount" min="10000" required placeholder="0"
                                class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-2xl px-6 py-4 pl-12 text-2xl font-bold text-white focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all placeholder:text-zinc-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        @foreach([50000, 100000, 250000, 500000] as $preset)
                            <button type="button" onclick="this.form.amount.value = {{ $preset }}" class="p-3 bg-white/5 border border-white/5 rounded-xl text-xs font-bold text-zinc-400 hover:bg-white/10 hover:border-amber-400/50 hover:text-white transition-all">
                                + Rp {{ number_format($preset, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>

                    <div class="p-4 bg-zinc-950/50 rounded-2xl border border-white/5 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-zinc-500">Biaya Admin</span>
                            <span class="text-green-400 font-bold uppercase tracking-widest">Gratis</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-zinc-500">Metode</span>
                            <span class="text-white font-bold">Simulasi Gateway</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-sm uppercase font-black tracking-widest shadow-[0_10px_30px_rgba(251,191,36,0.3)]">
                        Konfirmasi & Bayar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
