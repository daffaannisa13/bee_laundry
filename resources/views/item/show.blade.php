@extends('layouts.app')

@section('title', 'Item Detail')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="items-page">
<div class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('item.index') }}">Items</a> / <span>Item Detail</span>
    </div>

    <div class="header">Item Detail</div>

    <div class="create-item-container">
        <!-- Detail Section (Left) -->
        <div class="item-form-section" style="flex: 1;">
            <div class="form-header">
                <h3>Detail Item</h3>
            </div>

            <div class="form-group">
                <label>Nama Layanan</label>
                <input type="text" value="{{ $item->nama_service }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="text" value="Rp {{ number_format($item->harga,0,",",".") }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Tipe Layanan</label>
                <input type="text" value="{{ $item->tipe_service }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" rows="3" disabled>{{ $item->deskripsi }}</textarea>
            </div>

            <!-- Back Button -->
            <div class="form-actions" style="margin-top: 20px;">
                <a href="{{ route('item.index') }}" class="btn-create">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Right Animation Section (Optional) -->
        <div class="right-animation" style="flex: 1;">
            <div id="items-lottie-show"></div>
        </div>
    </div>
</div>
</div>

<!-- Lottie JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.10.2/lottie.min.js"></script>
<script>
    lottie.loadAnimation({
        container: document.getElementById('items-lottie-show'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '{{ asset("assets/animations/laundry.json") }}' // pakai animasi laundry
    });
</script>
@endsection
