<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Transaction;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        // Pastikan pembeli adalah user yang sedang login
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        // Pastikan belum pernah direview
        $exists = Review::where('transaction_id', $transaction->id)->exists();
        if ($exists) {
            return back()->with('error', 'Anda sudah memberikan penilaian untuk transaksi ini.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'seller_id' => $transaction->seller_id,
            'transaction_id' => $transaction->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
