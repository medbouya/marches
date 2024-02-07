<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Marchés publics')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ asset('img/AdminLTELogo.png') }}" alt="Marchés publics Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Marchés publics</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Tableau de bord
              </p>
            </a>
          </li>
          <li class="nav-item">
            
          </li>
          <li class="nav-item">
            <a href="{{ route('markets.index') }}" class="nav-link">
              <i class="nav-icon fas fa-stream"></i>
              <p>
                Marchés
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tasks"></i>
                <p>
                    Référentiel
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item sub-nav">
                    <a href="{{ route('market-types.index') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Types de marchés
                      </p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('mode-passations.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Modes de passation
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('autorite-contractantes.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Autorités contractantes
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('cpmps.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      CPMPs
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('secteurs.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Secteurs
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('attributaires.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Attributaires
                    </p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('audit-settings.index') }}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Paramètres d'audit
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('header', '')</h1>
          </div>
          <div class="col-sm-6">
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        
        <div class="card-body">
          @if(Session::has('success'))
            <div class="alert alert-success">
              {{ Session::get('success') }}
            </div>
          @endif

          @if(Session::has('error'))
            <div class="alert alert-danger">
              {{ Session::get('error') }}
            </div>
          @endif
          @yield('content')
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    @php
        use Carbon\Carbon;
        $currentYear = Carbon::now()->year;
    @endphp
    <strong>Copyright &copy; {{ $currentYear }}. All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('js/demo.js') }}"></script>
</body>
</html>
