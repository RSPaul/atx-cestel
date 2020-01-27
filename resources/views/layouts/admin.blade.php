<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
	
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <!-- <link href="{{ asset('public/css/app.css') }}" rel="stylesheet"> -->
     <!-- Custom fonts for this template-->
    <link href="{{ asset('public/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <!-- <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet"> -->
    <!-- Custom styles for this template-->
     <link href="{{ asset('public/css/sb-admin.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{asset('public/css/sweetalert.css')}}" type="text/css" rel="stylesheet" />

    @yield('styles', '')
</head>
<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.html">Admin</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <!--   <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="#">Activity Log</a>
          <div class="dropdown-divider"></div> -->
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="{{url('/admin')}}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('admin-users')}}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Users</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('admin-chefs')}}">
          <i class="fas fa-fw fa-table"></i>
          <span>Chefs</span></a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="{{route('admin-bookings')}}">
          <i class="fas fa-fw fa-table"></i>
          <span>Bookings</span></a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="{{route('admin-trips')}}">
          <i class="fas fa-fw fa-table"></i>
          <span>Trips</span></a>
      </li>
     -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="payDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Payment Requests</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="payDropdown">
          <a class="dropdown-item" href="{{route('admin-new-requests')}}">New</a>
          <a class="dropdown-item" href="{{route('admin-old-requests')}}">Completed</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('admin-coupons')}}">
          <i class="fas fa-fw fa-table"></i>
          <span>Coupons</span></a>
      </li> 
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

          @yield('content')

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Your Website 2019</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
    <script src="{{ asset('public/js/jquery-2.2.3.min.js')}}"></script>
    <script src="{{ asset('public/js/jquery-ui.js')}}"></script>   
    <script src="{{ asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script> 
    <script src="{{ asset('public/vendor/jquery-easing/jquery.easing.min.js')}}"></script> 
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script> 
    <script src="{{ asset('public/vendor/datatables/jquery.dataTables.js')}}"></script> 
    <script src="{{ asset('public/vendor/datatables/dataTables.bootstrap4.js')}}"></script> 
    <script src="{{ asset('public/js/sb-admin.min.js')}}"></script>
    @yield('scripts', '')
</body>
</html>
