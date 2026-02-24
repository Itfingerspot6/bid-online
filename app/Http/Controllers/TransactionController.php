<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('buyer_id', auth()->id())
            ->orWhere('seller_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        Transaction::create([
            'buyer_id'    => auth()->id(),
            'seller_id'   => auth()->id(),
            'amount'      => $request->amount,
            'status'      => 'completed',
            'payment_ref' => 'DEP-' . strtoupper(uniqid()),
        ]);

        auth()->user()->increment('balance', $request->amount);

        return back()->with('success', 'Deposit berhasil!');
    }

    public function pay(Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back()->withErrors(['error' => 'Transaksi ini sudah diproses.']);
        }

        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'Pembayaran berhasil!');
    }
}