@extends('layouts.app')

@section('title', 'Payment Failed')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="{{ route('order.index') }}">Payment</a> / <span>Failed</span>
  </div>

  <div class="header">Pembayaran Gagal</div>

  <div class="checkout-container">
    <div class="checkout-modal">
      <div class="modal-header">
        <h2 class="modal-title">Gagal!</h2>
        <a href="{{ route('order.index') }}" class="close-btn">×</a>
      </div>

      <div class="modal-body text-center">
        <div class="failed-icon" style="font-size: 3rem; color: red; margin-bottom: 15px;">
          <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div class="failed-message">
          <p>Maaf! Pembayaran untuk pesanan <strong>#{{ $order->nomor_invoice }}</strong> gagal.</p>
          <p>Silakan coba lagi atau hubungi admin jika masalah terus berlanjut.</p>
        </div>
      </div>

      <div class="modal-actions text-center" style="margin-top: 20px;">
        <a href="{{ route('order.payment', $order->id) }}" class="btn btn-secondary">
          <i class="fa-solid fa-arrow-left"></i> Coba Lagi
        </a>
        <a href="{{ route('order.index') }}" class="btn btn-cancel">
          <i class="fa-solid fa-times"></i> Kembali ke Pesanan
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
