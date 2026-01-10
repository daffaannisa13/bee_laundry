@extends('layouts.app')

@section('title', 'User Detail')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="shows-page">
<div class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('users.index') }}">Users</a> / <span>User Detail</span>
    </div>

    <div class="header">User Detail</div>

    <div class="create-user-container">
        <!-- Detail Section (Left) -->
        <div class="user-form-section" style="flex: 1;">
            <div class="form-header">
                <h3>Detail User</h3>
            </div>

            <div class="form-group">
                <label>Name</label>
                <input type="text" value="{{ $user->name }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" value="{{ $user->email }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" value="{{ $user->address }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" value="{{ $user->phone }}" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Role</label>
                <input type="text" value="{{ ucfirst($user->role) }}" class="form-control" disabled>
            </div>

            <!-- Back Button -->
            <div class="form-actions" style="margin-top: 20px;">
                <a href="{{ route('users.index') }}" class="btn-add">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Right Animation Section (Optional) -->
        <div class="right-animation" style="flex: 1;">
            <div id="users-lottie-show"></div>
        </div>
    </div>
</div>
</div>

<!-- Lottie JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.10.2/lottie.min.js"></script>
<script>
    lottie.loadAnimation({
        container: document.getElementById('users-lottie-show'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '{{ asset("assets/animations/laundry.json") }}'
    });
</script>
@endsection
