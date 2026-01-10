@extends('layouts.app')

@section('title', 'Update Order')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="orders-page">
<div class="main-content">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
      <a href="{{ route('order.index') }}">Order</a> / <span>Update Order</span>
  </div>

  <div class="header">Update Order</div>

  <div class="create-order-container">
    <div class="order-form-section">
      <div class="form-header">
        <h3>Update Order Status</h3>
      </div>

      <form action="{{ route('order.update', $pesanan->id) }}" method="POST">
        @csrf
        @method('POST') {{-- Bisa juga pakai PATCH jika route mendukung --}}

        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" class="form-control" required>
            <option value="pending" {{ $pesanan->status=='pending'?'selected':'' }}>Pending</option>
            <option value="processing" {{ $pesanan->status=='processing'?'selected':'' }}>Processing</option>
            <option value="done" {{ $pesanan->status=='done'?'selected':'' }}>Done</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-update">
            <i class="fa-solid fa-edit"></i> Update Status
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
@endsection
