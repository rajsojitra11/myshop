<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Shop Dashboard')</title> <!-- Default Title -->


   
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{Route('dashboard')}}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
            
      @php
          $lowStockCount = 0;
          $lowStockItems = [];
          if (Auth::check()) {
              $userProducts = \App\Models\Product::where('user_id', Auth::id())->get();
              $soldQtyMap = \App\Models\InvoiceProduct::select('name', \Illuminate\Support\Facades\DB::raw('SUM(qty) as sold'))->groupBy('name')->pluck('sold', 'name');
              foreach($userProducts as $product) {
                  $available = $product->stock_quantity - ($soldQtyMap[$product->name] ?? 0);
                  if ($available < 10) {
                      $lowStockCount++;
                      $lowStockItems[] = [
                          'name' => $product->name,
                          'available' => $available
                      ];
                  }
              }
          }
      @endphp
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          @if($lowStockCount > 0)
            <span class="badge badge-warning navbar-badge">{{ $lowStockCount }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 400px; overflow-y: auto;">
          <span class="dropdown-item dropdown-header">{{ $lowStockCount }} Notifications</span>
          <div class="dropdown-divider"></div>
          @if($lowStockCount > 0)
            @foreach($lowStockItems as $item)
                <a href="{{ route('product.index') }}" class="dropdown-item text-danger">
                  <i class="fas fa-exclamation-circle mr-2"></i> {{ \Illuminate\Support\Str::limit($item['name'], 15) }}
                  <span class="float-right font-weight-bold text-sm">{{ $item['available'] }} pending</span>
                </a>
                <div class="dropdown-divider"></div>
            @endforeach
            <a href="{{ route('product.index') }}" class="dropdown-item dropdown-footer text-center">Manage Products</a>
          @else
            <a href="{{ route('product.index') }}" class="dropdown-item text-success">
              <i class="fas fa-check-circle mr-2"></i> All product stocks healthy!
            </a>
          @endif
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
      <li class="nav-item">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" role="button">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </li>
    
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>My Shop</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <img src="{{ Auth::user()->profile_image 
            ? asset('storage/profile_images/' . Auth::user()->profile_image) 
            : asset('dist/img/user1-128x128.jpg') }}"
           class="img-circle elevation-2"
           style="width:35px;height:35px;object-fit:cover;"
           alt="User Image">
        </div>
        <div class="info">
    <a href="#" class="d-block">
        {{ session('user')->name ?? 'Guest' }}
    </a>
</div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item menu-open">
            <a href="{{Route('dashboard')}}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class=""></i>
              </p>
            </a>
            
          </li>
          <li class="nav-item">
            <a href="{{ route('income.index') }}" class="nav-link {{ request()->routeIs('income.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-coins"></i>
                <p>Income</p>
            </a>
        </li>
          <li class="nav-item">
            <a href="{{Route('expense')}}" class="nav-link {{ request()->routeIs('expense') ? 'active' : '' }}">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Expense
                <i class="right fas fa-angle"></i>
              </p>
            </a>            
          </li>
          <li class="nav-item">
            <a href="{{Route('supplier')}}" class="nav-link {{ request()->routeIs('supplier') ? 'active' : '' }}">
              <i class="nav-icon fas fa-handshake"></i>
              <p>
                Supplier
                <i class="right fas fa-angle"></i>
              </p>
            </a>            
          </li>
        </li>
        <li class="nav-item">
          <a href="{{Route('product.index')}}" class="nav-link {{ request()->routeIs('product.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cubes"></i>
            <p>
              Product
              <i class="right fas fa-angle"></i>
            </p>
          </a>            
        </li>
        <li class="nav-item">
          <a href="{{Route('customer')}}" class="nav-link {{ request()->routeIs('customer') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Customers
              <i class="right fas fa-angle"></i>
            </p>
          </a>  
        </li>      
        <li class="nav-item">
          <a href="{{Route('invoice.create')}}" class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>
              Invoice
              <i class="right fas fa-angle"></i>
            </p>
          </a>  
        </li>     
                
          
        <li class="nav-item">
          <a href="{{Route('setting')}}" class="nav-link {{ request()->routeIs('setting') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              Settings
              <i class="right fas fa-angle"></i>
            </p>
          </a>            
        </li>
                   
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- Displaying page title dynamically using Blade -->
          <h1 class="m-0">@yield('page-title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <!-- Dynamically set breadcrumb active page -->
            <li class="breadcrumb-item active">@yield('page')</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Content Section -->
  <section class="content">
    <div class="container-fluid">
      @yield('content') <!-- Main content section will be injected here -->
    </div>
  </section>

</div>
<!-- /.content-wrapper -->
