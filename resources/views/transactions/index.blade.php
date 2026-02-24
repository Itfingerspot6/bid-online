@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="font-display text-3xl text-white mb-2">Transaksi</h1>
    <p class="text-zinc-400 mb-8">Riwayat transaksi kamu</p>

    {{-- Saldo --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 mb-8 flex items-center justify-between">
        <div>
            <p class="text-xs text-zinc-500 uppercase tracking-wider">Saldo Kamu</p>
            <p class="text-3xl font-semibold text-amber-400 mt-1">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</p>
        </div>
        <button onclick="document.getElementById('deposit-modal').classList.remove('hidden')" class="px-5 py-2.5 bg-amber-400 text-zinc-950 font-semibold rounded-lg hover:bg-amber-300 transition-colors text-sm">
            + Deposit
        </button>
    </div>

    {{-- Tabel Transaksi --}}
    @if($transactions->count() > 0)
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-zinc-800">
                    <tr>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Tipe</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Keterangan</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Jumlah</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Status</th>
                        <th class="text-left px-6 py-4 text-zinc-500 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach($transactions as $transaction)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $transaction->type === 'deposit' ? 'bg-green-500/10 text-green-400' : '' }}
                                    {{ $transaction->type === 'withdrawal' ? 'bg-red-500/10 text-red-400' : '' }}
                                    {{ $transaction->type === 'bid' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                    {{ $transaction->type === 'refund' ? 'bg-purple-500/10 text-purple-400' : '' }}
                                ">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-300">{{ $transaction->description ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold {{ in_array($transaction->type, ['deposit', 'refund']) ? 'text-green-400' : 'text-red-400' }}">
                                {{ in_array($transaction->type, ['deposit', 'refund']) ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $transaction->status === 'completed' ? 'bg-green-500/10 text-green-400' : '' }}
                                    {{ $transaction->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                    {{ $transaction->status === 'failed' ? 'bg-red-500/10 text-red-400' : '' }}
                                ">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-500">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-12 text-center text-zinc-600">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>Belum ada transaksi.</p>
        </div>
    @endif
</div>

{{-- Deposit Modal --}}
<div id="deposit-modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="font-display text-xl text-white mb-4">Deposit Saldo</h3>
        <form method="POST" action="{{ route('transactions.deposit') }}">
            @csrf
            <label class="block text-sm text-zinc-400 mb-2">Jumlah Deposit</label>
            <input type="number" name="amount" min="10000" placeholder="Minimal Rp 10.000"
                class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors mb-4">
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('deposit-modal').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 border border-zinc-700 text-zinc-300 rounded-lg hover:border-zinc-500 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-amber-400 text-zinc-950 font-semibold rounded-lg hover:bg-amber-300 transition-colors text-sm">
                    Deposit
                </button>
            </div>
        </form>
    </div>
</div>

@endsection