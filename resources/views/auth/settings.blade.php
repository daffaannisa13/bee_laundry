@extends('layouts.app')

@section('title', 'Account Settings')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="settings-page">
<div class="main-content">
  <!-- Breadcrumb -->
  <div class="breadcrumb">
      <a href="{{ route('dashboard') }}">Dashboard</a> / <span>Create User</span>
  </div>

  <div class="header">Account Settings</div>

  <div class="settings-container">
    <div class="settings-card">
      <div class="settings-header">
        <h3>Edit User</h3>
      </div>

     <form id="settings-form" method="POST" action="{{ route('account.update') }}">
    @csrf

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" class="form-control"
             value="{{ old('name', auth()->user()->name) }}" required>
    </div>

    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" id="address" name="address" class="form-control"
             value="{{ old('address', auth()->user()->address) }}" required>
    </div>

    <div class="form-group">
      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" class="form-control"
             value="{{ old('phone', auth()->user()->phone) }}" required>
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Leave empty to keep current password">
      <small class="form-hint">Only fill if you want to change password</small>
    </div>

    <div class="form-group">
      <label for="password_confirmation">Confirm Password</label>
      <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm new password">
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-accept">
        <i class="fa-solid fa-check"></i> Accept
      </button>
    </div>
</form>

    </div>
  </div>
</div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){

    $('#settings-form').submit(function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('account.update') }}",
            type: "POST", // pakai POST
            data: formData,
            success: function(res){
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Account updated successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr){
                let err = xhr.responseJSON?.errors;
                let errMsg = '';

                if(err){
                    for(let field in err){
                        errMsg += err[field] + "\n";
                    }
                } else {
                    errMsg = 'Something went wrong!';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errMsg
                });
            }
        });
    });

});
</script>
