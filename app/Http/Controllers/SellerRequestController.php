<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SellerRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->seller_status === 'pending') {
            return back()->with('info', 'Permintaan Anda sedang dalam proses peninjauan.');
        }

        if ($user->role === 'seller' && $user->seller_status === 'approved') {
            return back()->with('info', 'Anda sudah menjadi penjual.');
        }

        $user->update([
            'seller_status' => 'pending'
        ]);

        return back()->with('success', 'Permintaan menjadi penjual telah dikirim! Mohon tunggu persetujuan admin.');
    }
}
