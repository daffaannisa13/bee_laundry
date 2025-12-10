@extends('layouts.app')

@section('title', 'Edit User')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="main-content">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
      <a href="{{ route('users.index') }}">Users</a> / <span>Edit User</span>
  </div>

  <div class="header">Edit User</div>

  <div class="create-user-container">
    <!-- Form Section (Left) -->
    <div class="user-form-section">
      <div class="form-header">
        <h3>Edit User</h3>
      </div>

      <form id="user-update-form" action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('POST') <!-- tetap POST karena kita pakai AJAX -->

        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" class="form-control" placeholder="Masukkan alamat" value="{{ $user->address }}" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" class="form-control" placeholder="Masukkan nomor HP" value="{{ $user->phone }}" required>
        </div>

        @if(auth()->user()->role === 'admin')
<div class="form-group">
    <label for="role">Role</label>
    <select id="role" name="role" class="form-control">
        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
</div>
@endif


        <div class="form-actions">
          <button type="submit" class="btn-add">
            <i class="fa-solid fa-user-pen"></i> Update User
          </button>
        </div>
      </form>
    </div>

    <!-- Animation Section (Right) -->
    <div class="right-animation">
      <div id="users-lottie"></div>
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
    container: document.getElementById('users-lottie'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: '{{ asset("assets/animations/laundry.json") }}'
  });

  // AJAX Form Submit
  $('#user-update-form').submit(function(e){
    e.preventDefault();
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      success: function(res){
        if(res.success){
          Swal.fire({
            icon: 'success',
            title: 'User updated successfully',
            showConfirmButton: false,
            timer: 1800
          }).then(() => {
            window.location.href = "{{ route('users.index') }}";
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
