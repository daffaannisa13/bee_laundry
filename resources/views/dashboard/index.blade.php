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
        @php
          $services = [
            'Clothes' => 'fa-shirt',
            'Blanket' => 'fa-layer-group',
            'Bedcover' => 'fa-bed',
            'Shoes' => 'fa-shoe-prints'
          ];
        @endphp
        @foreach($services as $name => $icon)
        <div class="card">
          <i class="fa-solid {{ $icon }}"></i>
          <p>{{ $name }}</p>
        </div>
        @endforeach
      </div>

      <!-- Recent Orders -->
      <div class="recent-orders">
        <h3>📦 Recent Orders</h3>

        <!-- Recent Orders Table -->
<div class="dashboard-admin-table-container">
    <div class="dashboard-admin-table-header">
        <span>Order No.</span>
        <span>Date</span>
        <span>Status</span>
        <span>Total Price</span>
    </div>

    @foreach($recentOrders as $order)
    <div class="dashboard-admin-table-row">
        <span>#{{ $order->nomor_invoice }}</span>
        <span>{{ $order->created_at->format('M d, Y') }}</span>
        <span class="dashboard-admin-status 
            {{ $order->status == 'done' ? 'completed' : ($order->status == 'processing' ? 'processing' : 'pending') }}">
            {{ ucfirst($order->status) }}
        </span>
        <span>Rp {{ number_format($order->total_harga,0,',','.') }}</span>
    </div>
    @endforeach
</div>


        <a href="{{ route('order.index') }}" class="dashboard-admin-btn">View All</a>
      </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
      <!-- Welcome Panel -->
      <div class="panel-box welcome-box">
        <div class="panel-header">
          <i class="fa-solid fa-hand-sparkles"></i> Welcome
        </div>
        <div class="panel-content">
          <p>Hello, <b>{{ Auth::user()->name ?? 'Admin' }}</b> 👋</p>
          <p>Hope you have a great day!</p>
        </div>
      </div>

      <!-- Overview Panel -->
      <div class="panel-box">
        <div class="panel-header">
          <i class="fa-solid fa-chart-pie"></i> Overview
        </div>
        <div class="panel-content">
          <p><b>Total Services:</b> {{ $totalServices }}</p>
          <p><b>Total Orders:</b> {{ $totalOrders }}</p>
          <p><b>Total Users:</b> {{ $totalCustomers }}</p>
        </div>
      </div>

      <!-- Report Panel -->
      <div class="panel-box">
        <div class="panel-header">
          <i class="fa-solid fa-chart-column"></i> Report
        </div>
        <div class="panel-content">
          <p><b>Pending:</b> {{ $received }}</p>
          <p><b>In Progress:</b> {{ $inProgress }}</p>
          <p><b>Completed:</b> {{ $completed }}</p>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
