<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\DepositReceived;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // Set Midtrans Configuration
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        $transaction = Transaction::where('payment_ref', $orderId)->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return DB::transaction(function () use ($transaction, $transactionStatus, $fraudStatus, $notification) {
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $this->completePayment($transaction);
                }
            } else if ($transactionStatus == 'settlement') {
                $this->completePayment($transaction);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $transaction->update(['status' => 'failed']);
            } else if ($transactionStatus == 'pending') {
                $transaction->update(['status' => 'pending']);
            }

            return response()->json(['status' => 'success']);
        });
    }

    private function completePayment($transaction)
    {
        if ($transaction->status !== 'paid' && $transaction->status !== 'completed') {
            $transaction->update(['status' => 'completed']);
            
            $user = $transaction->buyer;
            $user->increment('balance', $transaction->amount);
            
            // Send Notification
            $user->notify(new DepositReceived($transaction->amount));
        }
    }
}
