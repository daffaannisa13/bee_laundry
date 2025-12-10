@extends('layouts.app')

@section('title', 'Login')
@section('body-class', 'auth-layout')

@section('content')

{{-- SWEETALERT SESSION EXPIRED --}}
@if(session('expired'))
<script>
Swal.fire({
    title: "Session Expired",
    text: "{{ session('expired') }}",
    icon: "warning",
    timer: 2500,
    showConfirmButton: false
});
</script>
@endif

<div class="auth-page">
    <div class="login-container">
        
        <div class="left-side">
            <h2>Welcome Back</h2>
            <p>Don’t have an account? <a href="{{ route('register') }}">Sign Up</a></p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="input-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email" 
                        required>
                </div>

                {{-- Password --}}
                <div class="input-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>

        <div class="right-side"></div>
    </div>
</div>
@endsection
