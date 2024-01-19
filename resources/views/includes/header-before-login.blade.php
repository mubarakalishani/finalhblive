<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/css/splide.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <!-- dashboard status cards cdn -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        @livewireStyles
        <!-- Scripts -->
        
        <script src="https://kit.fontawesome.com/891a7151bf.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

        <!-- Styles -->
        @livewireStyles
    </head>
   <body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area">
        <nav class="navbar navbar-expand-lg navbar-light">
          <div class="container-fluid  mx-2">
              <a href="/" class="navbar-brand">
                  <img src="images/logo.png"  alt="CoolBrand" style="">
                  <!-- <h5><span> <img src="images/logo1.png" height="28" alt="CoolBrand" style="width: 10%;"></span><b> Hand<em>Bucks</em></b></h5> -->
              </a>
              <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarCollapse">
                  <div class="navbar-nav ms-auto">
                    <li class="nav-item dropdown earning-types">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        Earn
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="/jobs">Find Jobs <span>262</span></a>
                        <a class="dropdown-item" href="/offers">Offerwalls <span>692</span></a>
                        <a class="dropdown-item" href="#">Faucet <span><i class="fa-solid fa-infinity"></i></span></a>
                        <a class="dropdown-item" href="#">Shortlinks <span>89</span></a>
                        <a class="dropdown-item" href="#">PTC <span>154</span></a>
                        <a class="dropdown-item" href="#">Games <span>523</span></a>
                      </div>
                    </li>
                    <a href="/login" class="nav-item nav-link aut-btn">Login</a>
                    <a href="/register" class="nav-item nav-link aut-btn">Sign up</a>
                  </div>
              </div>
          </div>
        </nav>
      </header>
      <!-- ***** Header Area End ***** -->