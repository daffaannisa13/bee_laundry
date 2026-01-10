@extends('layouts.app')

@section('title', 'Create User')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="creates-page">
<div class="main-content">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
      <a href="{{ route('users.index') }}">Users</a> / <span>Create User</span>
  </div>

  <div class="header">Create User</div>

  <div class="create-user-container">
    <!-- Form Section (Left) -->
    <div class="user-form-section">
      <div class="form-header">
        <h3>New User</h3>
      </div>

      <form id="user-form" action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" class="form-control" placeholder="Masukkan alamat" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" class="form-control" placeholder="Masukkan nomor HP" required>
        </div>

        @if(auth()->user()->role === 'admin')
<div class="form-group">
    <label for="role">Role</label>
    <select id="role" name="role" class="form-control">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
</div>
@endif


        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-add">
            <i class="fa-solid fa-user-plus"></i> Save User
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
  $('#user-form').submit(function(e){
    e.preventDefault();
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      success: function(res){
        if(res.success){
          Swal.fire({
            icon: 'success',
            title: 'User created successfully',
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
