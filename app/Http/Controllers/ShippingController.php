<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Notifications\ItemShipped;

class ShippingController extends Controller
{
    public function updateAddress(Request $request, Transaction $transaction)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:1000',
        ]);

        if (auth()->id() !== $transaction->buyer_id) {
            abort(403);
        }

        $transaction->update([
            'shipping_address' => $request->shipping_address,
            'status' => 'paid', // Or stay 'paid' if it was already
        ]);

        return back()->with('success', 'Alamat pengiriman berhasil diperbarui.');
    }

    public function updateTracking(Request $request, Transaction $transaction)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        if (auth()->id() !== $transaction->seller_id) {
            abort(403);
        }

        $transaction->update([
            'tracking_number' => $request->tracking_number,
            'status' => 'shipped',
        ]);

        // Notify buyer
        $transaction->buyer->notify(new ItemShipped($transaction));

        return back()->with('success', 'Nomor resi berhasil diupdate dan pembeli telah diberitahu.');
    }

    public function confirmReceipt(Transaction $transaction)
    {
        if (auth()->id() !== $transaction->buyer_id) {
            abort(403);
        }

        if ($transaction->status !== 'shipped') {
            return back()->with('error', 'Barang belum dikirim atau sudah selesai.');
        }

        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'Transaksi selesai! Terima kasih telah mengkonfirmasi penerimaan barang.');
    }
}
