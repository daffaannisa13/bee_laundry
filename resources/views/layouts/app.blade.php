<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Bee Laundry')</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body class="@yield('body-class', '')">

@if(session('expired'))
<script>
    Swal.fire({
        title: "Session Expired",
        text: "{{ session('expired') }}",
        icon: "warning",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

  {{-- Sidebar --}}
  @unless(Request::is('login', 'register'))
    @include('partials.sidebar')
  @endunless

{{-- SweetAlert Success --}}
  @if(session('success'))
  <script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
  </script>
  @endif

  {{-- SweetAlert Error --}}
  @if(session('error'))
  <script>
    Swal.fire({
        title: 'Gagal!',
        text: "{{ session('error') }}",
        icon: 'error',
        timer: 1500,
        showConfirmButton: false
    });
  </script>
  @endif


  <div class="main-container">
      @yield('content')
  </div>

  @stack('scripts')

</body>
</html>
