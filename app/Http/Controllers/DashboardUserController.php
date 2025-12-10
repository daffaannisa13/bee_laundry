<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan; // ← ini yang hilang

class DashboardUserController extends Controller
{
    public function index() {
        $userId = auth()->id();

        $myOrdersCount = Pesanan::where('user_id', $userId)->count();
        $pendingPaymentCount = Pesanan::where('user_id', $userId)->where('status', 'pending')->count();
        $onDeliveryCount = Pesanan::where('user_id', $userId)->where('status', 'processing')->count();
        $completedCount = Pesanan::where('user_id', $userId)->where('status', 'done')->count();

        $recentOrders = Pesanan::where('user_id', $userId)
            ->orderBy('created_at','desc')
            ->take(5)
            ->get();

        return view('dashboard.user', compact(
            'myOrdersCount',
            'pendingPaymentCount',
            'onDeliveryCount',
            'completedCount',
            'recentOrders'
        ));
    }
}
