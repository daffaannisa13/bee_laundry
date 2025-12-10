<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PesananLaundry;
use App\Models\User;
use App\Models\Item;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Recent Orders (ambil 5 terbaru)
        $recentOrders = Pesanan::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Overview Metrics
        $totalServices = Item::count();      // jumlah jenis layanan laundry
        $totalOrders = Pesanan::count();     // total pesanan
        $totalCustomers = User::count();     // total semua user

        // Report Metrics
        $received = Pesanan::where('status','pending')->count();
        $inProgress = Pesanan::where('status','processing')->count();
        $completed = Pesanan::where('status','done')->count();

        return view('dashboard.index', compact(
            'recentOrders',
            'totalServices',
            'totalOrders',
            'totalCustomers',
            'received',
            'inProgress',
            'completed'
        ));
    }
}
