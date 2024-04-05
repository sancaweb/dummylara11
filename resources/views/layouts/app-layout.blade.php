<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle . ' || ' . config('app.name') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/fontawesome/css/all.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/select2/css/select2.min.css') }}">

    @if ($page == 'user')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('v1/plugins/datatables/datatables.min.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('v1/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('v1/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('v1/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('v1/dist/css/adminlte.min.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed dark-mode">
    <div class="wrapper">
        <!-- Navbar -->
        <x-navbar>

        </x-navbar>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"> {{ $pageTitle }}</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            {{-- <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Layout</a></li>
              <li class="breadcrumb-item active">Top Navigation</li>
            </ol> --}}
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                @yield('content')
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('v1/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('v1/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('v1/plugins/datatables/datatables.min.js') }}"></script>
    {{-- <script src="{{ asset('v1/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> --}}

    <!-- AdminLTE App -->
    <script src="{{ asset('v1/dist/js/adminlte.min.js') }}"></script>

    <!-- SELECT2 -->
    <script src="{{ asset('v1/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        let base_url = "{{ route('root') }}";
        // $('[data-toggle="tooltip"]').tooltip()

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    @vite(['resources/js/pages/user.js'])
</body>

</html>
