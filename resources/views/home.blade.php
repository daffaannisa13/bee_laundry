@extends('layouts.app')

@section('body-class', 'no-sidebar')

@section('content')
    <div class="home-page">
        <div class="main">
            <div class="content">
                <h1>Welcome to Our Application</h1>
                <p>Click the button below to start the program</p>
                <a href="{{ route('login') }}" class="btn btn-start">Get Started</a>
            </div>
        </div>
    </div>
@endsection
