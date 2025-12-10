@extends('layouts.app')

@section('title', 'Edit Layanan')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('item.index') }}">Laundry Items</a> / <span>Edit Layanan</span>
    </div>

    <div class="header">Edit Laundry Item</div>

    <div class="create-item-container">
        <!-- Form Section (Left) -->
        <div class="item-form-section">
            <div class="form-header">
                <h3>Edit Laundry Item</h3>
            </div>

            <form id="item-update-form" action="{{ route('item.update', $item->id) }}" method="POST">
                @csrf
                @method('POST') <!-- tetap POST karena pakai AJAX -->

                <div class="form-group">
                    <label for="nama_service">Nama Layanan</label>
                    <input type="text" id="nama_service" name="nama_service" class="form-control" 
                           placeholder="Masukkan nama layanan" value="{{ $item->nama_service }}" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" class="form-control" 
                           placeholder="Masukkan harga" value="{{ $item->harga }}" required>
                </div>

                <div class="form-group">
                    <label for="tipe_service">Tipe Layanan</label>
                    <select id="tipe_service" name="tipe_service" class="form-control">
                        <option value="Wash & Iron" {{ $item->tipe_service == 'Wash & Iron' ? 'selected' : '' }}>Wash & Iron</option>
                        <option value="Dry Cleaning" {{ $item->tipe_service == 'Dry Cleaning' ? 'selected' : '' }}>Dry Cleaning</option>
                        <option value="Iron Only" {{ $item->tipe_service == 'Iron Only' ? 'selected' : '' }}>Iron Only</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi">{{ $item->deskripsi }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-add">
                        <i class="fa-solid fa-pen-to-square"></i> Update Item
                    </button>
                </div>
            </form>
        </div>

        <!-- Animation Section (Right) -->
        <div class="right-animation">
            <div id="laundry-lottie"></div>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.10.2/lottie.min.js"></script>

<script>
    // Lottie Animation
    lottie.loadAnimation({
        container: document.getElementById('laundry-lottie'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '{{ asset("assets/animations/laundry.json") }}'
    });

    // AJAX Form Submit
    $('#item-update-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Item updated successfully',
                        showConfirmButton: false,
                        timer: 1800
                    }).then(() => {
                        window.location.href = "{{ route('item.index') }}";
                    });
                }
            },
            error: function(xhr){
                let msg = "Something went wrong!";
                if(xhr.responseJSON?.errors){
                    msg = Object.values(xhr.responseJSON.errors).flat().join("\n");
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg
                });
            }
        });
    });
</script>
@endsection
