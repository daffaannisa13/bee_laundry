@extends('layouts.app')

@section('title', 'Check Out')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="{{ route('order.index') }}">Payment</a> / <span>Check Out</span>
  </div>

  <div class="header">Check Out</div>

  <div class="checkout-container">
    <div class="checkout-modal">
      <div class="modal-header">
        <h2 class="modal-title">Check Out</h2>
        <a href="{{ route('order.index') }}" class="close-btn">×</a>
      </div>

      <div class="modal-body">
        <div class="order-info">
          <div class="order-number">Payment #{{ $order->nomor_invoice ?? '0001' }}</div>
          <div class="checkout-total">
    <div class="checkout-label">Total Payment</div>
    <div class="checkout-amount">
        Rp {{ number_format($total ?? 0, 0, ',', '.') }}
    </div>
</div>

        </div>

        <!-- Form langsung submit ke controller processPayment -->
        <form action="{{ route('order.processPayment', $order->id) }}" method="POST">
          @csrf
<input type="hidden" name="payment_method" value="XENDIT">

          <div class="modal-actions">
    <button type="submit" class="btn btn-confirm">
        <i class="fa-solid fa-check"></i> Proceed to Payment
    </button>
</div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
