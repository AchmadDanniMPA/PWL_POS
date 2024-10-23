<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'PWL Laravel Starter Code') }}</title>

  <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- untuk mengirim token Laravel CSRF pada setiap request AJAX -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href={{ asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}>
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href={{ asset('adminlte/dist/css/adminlte.min.css')}}>

  @stack('css') <!-- untuk memanggil custom CSS dari perintah push('css') pada masing-masing view -->
  <style>
    body, html {
        height: 100%;
        margin: 0;
        font-family: 'Source Sans Pro', sans-serif;
        background: linear-gradient(135deg, #00c6ff, #007f66);
        background-attachment: fixed;
        background-size: cover;
        color: white;
    }
    .navbar {
        background-color: transparent !important;
        color: white !important;
    }
    .navbar a, .navbar .nav-link {
        color: white !important;
    }
    .sidebar-dark-primary {
        background: rgba(0, 0, 0, 0.7);
    }
    .brand-link {
        background-color: rgba(0, 0, 0, 0.5);
    }
    .content-wrapper {
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .main-footer {
        background: transparent;
        color: white;
    }
    .breadcrumb-item.active {
        color: #00c6ff !important;
    }
    body, html, .content-wrapper, .sidebar, .navbar, .footer, .breadcrumb, .dropdown-menu {
        color: #333333 !important;
    }
    .navbar a, .navbar .nav-link {
        color: #ffffff !important;
    }
    .btn {
        color: #ffffff !important;
    }
    .sidebar-dark-primary .nav-link {
        color: #ffffff !important;
    }
    .main-footer {
        background: transparent;
        color: #333333 !important;
    }
    .breadcrumb-item.active {
        color: #007f66 !important;
    }
    .sidebar-dark-primary .nav-treeview > .nav-item > .nav-link {
        color: #c2c7d0 !important;
    }
    .dropdown-menu {
        background-color: #333333 !important;
        color: #ffffff !important;
    }
    .dropdown-menu a {
        color: #ffffff !important;
    }
    .dropdown-menu a:hover {
        background-color: #00c6ff !important;
        color: #ffffff !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  @include('layouts.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PWL - Starter Code</span>
    </a>

    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layouts.breadcrumb')

    <!-- Main content -->
    <section class="content">
        @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('layouts.footer')
</div>
<!-- ./wrapper -->

<!-- Modal structure for loading content -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- Content will be dynamically loaded via AJAX -->
    </div>
  </div>
</div>

<!-- jQuery -->
<script src={{ asset('adminlte/plugins/jquery/jquery.min.js')}}></script>
<!-- Bootstrap 4 -->
<script src={{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}></script>
<!-- DataTables & Plugins -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- jQuery Validation -->
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- AdminLTE App -->
<script src={{ asset('adminlte/dist/js/adminlte.min.js')}}></script>

<script>
    // Mengirimkan token Laravel CSRF pada setiap request AJAX
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // AJAX logic for profile picture upload or any other modal usage can go here
    $(document).ready(function() {
        $('#upload-photo-link').click(function() {
            $.ajax({
                url: "{{ url('/profile') }}",  // Fetch the profile upload view
                type: "GET",
                success: function(response) {
                    $('#myModal').html(response);  // Load the response (HTML form) into the modal
                    $('#myModal').modal('show');   // Show the modal with the profile form
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load the profile upload form.'
                    });
                }
            });
        });
    });
</script>

@stack('js') <!-- untuk memanggil custom js dari perintah push('js') pada masing-masing view -->
</body>
</html>
