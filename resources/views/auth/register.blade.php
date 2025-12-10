@extends('layouts.app')

@section('title', 'Register')
@section('body-class', 'auth-layout')

@section('content')
<div class="auth-page">
    <div class="register-container">

        <div class="left-side">
            <h2>Get Started</h2>
            <p>Already have an account? <a href="{{ route('login') }}">Log In</a></p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="input-group">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Name" 
                        required>
                </div>

                {{-- Address --}}
                <div class="input-group">
                    <label for="address">Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address"
                        placeholder="Address"
                        required>
                </div>

                {{-- Phone --}}
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        placeholder="Phone Number" 
                        required>
                </div>

                {{-- Email --}}
                <div class="input-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Email" 
                        required>
                </div>

                {{-- Password --}}
                <div class="input-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="*Password length (10-32)" 
                        required>
                </div>

                <button type="submit" class="btn-register">Sign Up</button>
            </form>
        </div>

        <div class="right-side"></div>
    </div>
</div>

{{-- CDN SweetAlert HANYA untuk halaman ini --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Alert jika register berhasil --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Register Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false,
    });
</script>
@endif
@endsection
