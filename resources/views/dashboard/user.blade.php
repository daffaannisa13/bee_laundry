@extends('layouts.app')

@section('title', 'Dashboard')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">
  <div class="header">Dashboard</div>

  <div class="dashboard">

    <!-- Left Content -->
    <div class="left-content">

      <!-- Cards -->
      <div class="cards">
        <div class="card">
          <i class="fa-solid fa-box"></i>
          <p>My Orders</p>
          <span class="card-count">{{ $myOrdersCount ?? 0 }}</span>
        </div>
        <div class="card">
          <i class="fa-solid fa-credit-card"></i>
          <p>Pending Payment</p>
          <span class="card-count">{{ $pendingPaymentCount ?? 0 }}</span>
        </div>
        <div class="card">
          <i class="fa-solid fa-truck"></i>
          <p>On Delivery</p>
          <span class="card-count">{{ $onDeliveryCount ?? 0 }}</span>
        </div>
        <div class="card">
          <i class="fa-solid fa-circle-check"></i>
          <p>Completed</p>
          <span class="card-count">{{ $completedCount ?? 0 }}</span>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="recent-orders">
    <h3>🛒 My Recent Orders</h3>

    <div class="dashboard-admin-table-container">
        <div class="dashboard-admin-table-header">
            <span>Order No.</span>
            <span>Date</span>
            <span>Status</span>
            <span>Total</span>
        </div>

        @forelse($recentOrders as $order)
            @php
                // Tentukan class dan teks status
                $statusClass = match($order->status) {
                    'pending' => 'pending',
                    'processing' => 'processing',
                    'done' => 'completed',
                    default => 'unknown',
                };
                $statusText = match($order->status) {
                    'pending' => 'Pending Payment',
                    'processing' => 'On Delivery',
                    'done' => 'Completed',
                    default => ucfirst($order->status),
                };
            @endphp
            <div class="dashboard-admin-table-row">
                <span>#{{ $order->nomor_invoice ?? 'INV-'.$order->id }}</span>
                <span>{{ $order->created_at->format('M d, Y') }}</span>
                <span class="status {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                <span>Rp {{ number_format($order->total_harga,0,',','.') }}</span>
            </div>
        @empty
            <div class="dashboard-admin-table-row">
                <span colspan="4" style="text-align:center;">You have no recent orders.</span>
            </div>
        @endforelse
    </div>

    <a href="{{ url('/user/orders?user_id=' . auth()->id()) }}" class="dashboard-admin-btn">View All</a>
</div>


    </div>

    <!-- Right Panel -->
    <div class="right-panel">

      <!-- Welcome Panel -->
      <div class="panel-box welcome-box">
        <div class="panel-header">
          <i class="fa-solid fa-user"></i> Welcome
        </div>
        <div class="panel-content">
          <p>Hello, <b>{{ Auth::user()->name ?? 'User' }}</b> 👋</p>
          <p>Glad to have you back!</p>
        </div>
      </div>

      <!-- Account Info Panel -->
      <div class="panel-box">
        <div class="panel-header">
          <i class="fa-solid fa-id-card"></i> Account Info
        </div>
        <div class="panel-content">
          <p><b>Email:</b> {{ Auth::user()->email }}</p>
          <p><b>Member Since:</b> {{ Auth::user()->created_at->format('Y') }}</p>
          <p><b>Status:</b> Verified ✔️</p>
        </div>
      </div>

      <div class="panel-box">
  <div class="panel-header">
    <i class="fa-solid fa-chart-line"></i> My Stats
  </div>
  <div class="panel-content">
    <p><i class="fa-solid fa-box"></i> Total Orders: <b>{{ $myOrdersCount ?? 0 }}</b></p>
    <p><i class="fa-solid fa-credit-card"></i> Pending: <b>{{ $pendingPaymentCount ?? 0 }}</b></p>
    <p><i class="fa-solid fa-truck"></i> On Delivery: <b>{{ $onDeliveryCount ?? 0 }}</b></p>
    <p><i class="fa-solid fa-circle-check"></i> Completed: <b>{{ $completedCount ?? 0 }}</b></p>
  </div>
</div>


    </div>
  </div>
</div>
@endsection
