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
        $amount = (int) str_replace(['.', ','], '', $request->amount);

        if ($amount < 10000) {
            return response()->json(['error' => 'Minimal deposit Rp 10.000'], 422);
        }

        // Set Midtrans Configuration
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        $orderId = 'DEP-' . strtoupper(uniqid()) . '-' . rand(100, 999);

        $transaction = Transaction::create([
            'buyer_id'    => auth()->id(),
            'seller_id'   => auth()->id(), // Deposit is to self
            'amount'      => $amount,
            'status'      => 'pending',
            'payment_ref' => $orderId,
            'auction_id'  => null, // Corrected via migration
            'type'        => 'deposit',
            'description' => 'Deposit Saldo via Midtrans',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$amount,
            ],
            'item_details' => [
                [
                    'id' => 'DEP01',
                    'price' => (int)$amount,
                    'quantity' => 1,
                    'name' => 'Deposit Saldo BidOnline',
                ]
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkStatus(Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        // Set Midtrans Configuration
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($transaction->payment_ref);
            
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->completePayment($transaction);
                    return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
                }
            } else if ($transactionStatus == 'settlement') {
                $this->completePayment($transaction);
                return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
            } else if ($transactionStatus == 'pending') {
                return back()->with('info', 'Pembayaran masih menunggu.');
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $transaction->update(['status' => 'failed']);
                return back()->with('error', 'Pembayaran gagal atau kedaluwarsa.');
            }

            return back()->with('info', 'Status transaksi: ' . $transactionStatus);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengecek status: ' . $e->getMessage());
        }
    }

    private function completePayment($transaction)
    {
        if ($transaction->status !== 'paid' && $transaction->status !== 'completed') {
            $transaction->update(['status' => 'completed']);
            
            $user = $transaction->buyer;
            $user->increment('balance', $transaction->amount);
            
            // Send Notification
            $user->notify(new \App\Notifications\DepositReceived($transaction->amount));
        }
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