@extends('layouts.app')

@section('title', 'Payment - Order Process')
@section('content')

<div class="payment-container">
    <div class="payment-header">
        <h1>Payment</h1>
    </div>

    <div class="stepper-container">

        {{-- Stepper --}}
        <div class="stepper">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">Customer</div>
                <div class="step-line"></div>
            </div>

            <div class="step active">
                <div class="step-number">2</div>
                <div class="step-label">Order ID</div>
                <div class="step-line"></div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Payment</div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('order.payment', $order->id) }}" method="POST">
            @csrf

            {{-- Row Input --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Customer</label>
                    <input type="text" value="{{ $customer->name }}" class="form-control" disabled>
                </div>

                <div class="form-group">
                    <label>Order ID</label>
                    <input type="text" value="{{ $order->id }}" class="form-control" disabled>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="content-area">

                {{-- LEFT - Order Details --}}
                <div class="content-box">
                    <h3>Order Details</h3>

                    @if($orderItems->count() == 0)
                        <div class="empty-state">No items.</div>
                    @else
                        <table class="table table-bordered mt-2">
                            <thead class="table-light">
                                        <tr>
                                            {{-- PERBAIKAN: Alignment untuk Header --}}
                                            <th class="text-start align-middle">Service</th> 
                                            <th class="text-center align-middle">Qty</th>
                                            <th class="text-end align-middle">Price</th>
                                            <th class="text-end align-middle">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $grand = 0; @endphp
                                        @foreach($orderItems as $item)
                                            @php
                                                // Menggunakan field yang benar dari PesananLaundry
                                                $qty = $item->jumlah ?? 0;
                                                $harga = $item->harga_item ?? 0;
                                                $total = $qty * $harga;
                                                $grand += $total;
                                            @endphp
                                            <tr>
                                                {{-- Data Baris --}}
                                                <td>
<span class="badge bg-info text-dark ms-2">
    {{ optional($item->Item)->tipe_service ?? 'N/A' }}
</span>

                                                </td>
                                                <td class="text-center align-middle">{{ $qty }}</td>
                                                <td class="text-end align-middle">Rp {{ number_format($harga,0,',','.') }}</td>
                                                <td class="text-end align-middle">Rp {{ number_format($total,0,',','.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                        </table>
                    @endif
                </div>

                {{-- RIGHT - Payment Info --}}
                <div class="content-box">
                    <h3>Payment Information</h3>

                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                    <p><strong>Address:</strong> {{ $customer->address }}</p>
                </div>

            </div>

            {{-- Buttons --}}
            <div class="button-group">
                <a href="{{ route('order.checkout', $order->id) }}" class="btn btn-primary">Proceed to Pay</a>
            </div>

        </form>

    </div>
</div>

<style>
    .table-order-details {
        /* Memaksa tata letak tabel tetap untuk menghormati lebar kolom */
        table-layout: fixed;
        width: 100%;
    }
    .table-order-details th:nth-child(1),
    .table-order-details td:nth-child(1) { /* Service column */
        width: 45%; 
    }
    .table-order-details th:nth-child(2),
    .table-order-details td:nth-child(2) { /* Qty column */
        width: 10%;
    }
    .table-order-details th:nth-child(3),
    .table-order-details td:nth-child(3) { /* Price column */
        width: 22.5%; 
    }
    .table-order-details th:nth-child(4),
    .table-order-details td:nth-child(4) { /* Total column */
        width: 22.5%; 
    }
</style>

@endsection
