@extends('layouts.app')

@section('title', 'Payment Success')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">

  <div class="breadcrumb">
    <a href="{{ route('order.index') }}">Payment</a> / <span>Success</span>
  </div>

  <div class="header">Pembayaran Berhasil</div>

  <!-- FIX: container center -->
  <div class="checkout-container" style="display:flex; justify-content:center;">

    <!-- FIX: modal center -->
    <div class="checkout-modal" style="max-width:450px; width:100%; margin:auto;">

      <div class="modal-header" style="text-align:center;">
        <h2 class="modal-title">Sukses!</h2>
        <a href="{{ route('order.index') }}" class="close-btn">×</a>
      </div>

      <div class="modal-body" style="padding: 30px 20px; text-align:center;">

        <!-- ICON CENTER -->
        <div style="margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"
               style="font-size:4rem; color:#28a745;"></i>
        </div>

        <!-- PESAN CENTER -->
        <div class="success-message" style="font-size:16px; color:#444; text-align:center;">
          <p>
            Terima kasih! Pesanan 
            <strong>#{{ $order->nomor_invoice }}</strong>
            telah berhasil dibayar.
          </p>
        </div>

      </div>

      <div class="modal-actions" style="text-align:center; margin-bottom:25px;">
        <a href="{{ route('order.index') }}" class="btn btn-confirm">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Pesanan
        </a>
      </div>

    </div>
  </div>

</div>
@endsection
