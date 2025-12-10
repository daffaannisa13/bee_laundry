@extends('layouts.app')

@section('title', 'Order Detail')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('order.index') }}">Orders</a> / <span>Order Detail</span>
    </div>

    <div class="header">Order Detail</div>

    <div class="create-user-container" style="display:flex; gap:20px;">
        <!-- Left Section: Detail Order -->
        <div class="order-form-section" style="flex:2;">
            <div class="form-header">
                <h3>Detail Order</h3>
            </div>

            <div class="form-group">
                <label>Order ID</label>
                <input type="text" value="{{ $pesanan->nomor_invoice }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" value="{{ $pesanan->user->name }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" value="{{ $pesanan->alamat }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" value="{{ $pesanan->user->phone }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea class="form-control" disabled rows="4">{{ $pesanan->catatan ?? '-' }}</textarea>
            </div>

            <div class="form-group">
               <label>Ordered Items</label>
               <table class="form-control" style="width:100%; border-collapse: collapse;">
                   <thead>
                       <tr>
                           <th style="border-bottom:1px solid #ccc; padding:8px; text-align:left;">Item</th>
                           <th style="border-bottom:1px solid #ccc; padding:8px; text-align:center;">Qty</th>
                           <th style="border-bottom:1px solid #ccc; padding:8px; text-align:right;">Price</th>
                           <th style="border-bottom:1px solid #ccc; padding:8px; text-align:right;">Subtotal</th>
                       </tr>
                   </thead>
                   <tbody>
                       @foreach($pesanan->items as $item)
                       <tr>
                           <td style="padding:8px;">{{ $item->Item->nama_service }}</td>
                           <td style="padding:8px; text-align:center;">{{ $item->jumlah }}</td>
                           <td style="padding:8px; text-align:right;">Rp {{ number_format($item->harga_item,0,',','.') }}</td>
                           <td style="padding:8px; text-align:right;">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>
            </div>
        </div>

        <!-- Right Section: Summary + Payment -->
        <div class="order-summary-section" style="flex:1; display:flex; flex-direction:column; gap:20px;">
            <div class="summary-box" style="padding:20px; border:1px solid #ccc; border-radius:8px;">
                <h3>Summary</h3>
                <div class="summary-item">
                    <strong>Status:</strong>
                    <span class="status 
                        {{ $pesanan->status == 'done' ? 'completed' : ($pesanan->status == 'processing' ? 'processing' : 'pending') }}">
                        {{ ucfirst($pesanan->status) }}
                    </span>
                </div>

                <div class="summary-item" style="margin-top:10px;">
                    <strong>Total Price:</strong> Rp {{ number_format($pesanan->total_harga,0,',','.') }}
                </div>
            </div>

            <!-- Payment Info -->
            <div class="payment-box" style="padding:20px; border:1px solid #ccc; border-radius:8px;">
                <h3>Payment</h3>
                @if($pesanan->pembayaran)
                    <div class="payment-item" style="margin-bottom:5px;">
                        <strong>Status:</strong>
                        <span class="status 
                            {{ $pesanan->pembayaran->status == 'paid' ? 'completed' : ($pesanan->pembayaran->status == 'pending' ? 'pending' : 'processing') }}">
                            {{ ucfirst($pesanan->pembayaran->status) }}
                        </span>
                    </div>
                    <div class="payment-item" style="margin-bottom:5px;">
                        <strong>Amount:</strong> Rp {{ number_format($pesanan->pembayaran->jumlah,0,',','.') }}
                    </div>
                    <div class="payment-item" style="margin-bottom:5px;">
                        <strong>Method:</strong> {{ ucfirst($pesanan->pembayaran->metode) }}
                    </div>
                    <div class="payment-item" style="margin-bottom:5px;">
                        <strong>Payment Date:</strong> {{ $pesanan->pembayaran->created_at->format('d M Y H:i') }}
                    </div>
                @else
                    <div class="payment-item">
                        <strong>Status:</strong> <span class="status pending">Not Paid</span>
                    </div>
                @endif

                
            <div class="form-actions">
                <a href="{{ route('order.index') }}" class="btn-add">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
            </div>

        </div>
    </div>
</div>
@endsection
