@extends('layouts.app')

@section('title', 'Tambah Layanan')

@section('content')
<div class="main-content">
    <div class="breadcrumb">
      <a href="{{ route('item.index') }}">Item</a> / <span>Create Item</span>
  </div>
    <!-- Header -->
    <div class="header">Create Laundry Item</div>

    <!-- Form & Animation Container -->
    <div class="create-item-container" style="display: flex; gap: 30px; flex-wrap: wrap;">
        <!-- Form Section -->
        <div class="item-form-section" style="flex: 1; min-width: 300px;">
            <div class="form-header">
                <h3>Tambah Laundry Item</h3>
            </div>

            <form id="item-create-form" action="{{ route('item.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label for="nama_service">Nama Layanan</label>
                    <input type="text" id="nama_service" name="nama_service" class="form-control" 
                           placeholder="Masukkan nama layanan" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" class="form-control" 
                           placeholder="Masukkan harga" required>
                </div>

                <div class="form-group">
                    <label for="tipe_service">Tipe Layanan</label>
                    <select id="tipe_service" name="tipe_service" class="form-control">
                        <option value="Wash & Iron">Wash & Iron</option>
                        <option value="Dry Cleaning">Dry Cleaning</option>
                        <option value="Iron Only">Iron Only</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" 
                              placeholder="Masukkan deskripsi"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-create">
                        <i class="fa-solid fa-plus"></i> Tambah Item
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
    $('#item-create-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Item berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1800
                    }).then(() => {
                        window.location.href = "{{ route('item.index') }}";
                    });
                }
            },
            error: function(xhr){
                let msg = "Terjadi kesalahan!";
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
