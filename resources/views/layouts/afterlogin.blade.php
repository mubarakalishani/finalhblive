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
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

        <!-- Styles -->
        @livewireStyles
        <style>
            .edit-box {
                display: none;
                position: absolute;
                top: 0;
                left: 50%;
                padding: 10px;
                border: 1px solid #ccc;
                background-color: #fff;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
   <body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#" class="navbar-brand">
                        <img src="{{ asset('images/logo.png') }}"  alt="CoolBrand" style="">
                        <!-- <h5 style="color:#0098d8;"><span> <i class="fa fa-firefox" aria-hidden="true" style="color:#0098d8;"></i></span><b>  Bucksbite</b></h5> -->
                    </a>
                </div>
                <!-- Sidebar Navigation -->
                <ul class="sidebar-nav">
                    <!-- <li class="sidebar-header">
                        Chose & Earn
                    </li> -->
                    <li class="sidebar-item">
                        <a href="/dashboard" class="sidebar-link">
                            <i class="fa-solid fa-home pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/advertiser/create-new-task" class="sidebar-link"><i class="fa-solid fa-file-circle-plus"></i> Post new job</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#pages"
                            aria-expanded="false" aria-controls="pages">
                            <i class="fa-solid fa-earth-americas"></i>
                            Earn
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="/jobs" class="sidebar-link"><i class="fa-solid fa-list-check"></i> Find Jobs</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/offers" class="sidebar-link"><i class="fa-brands fa-buffer"></i> Offerwalls</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="all-surveys.html" class="sidebar-link"><i class="fa-solid fa-square-poll-vertical"></i> Surveys</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/faucet" class="sidebar-link"><i class="fa-solid fa-faucet-drip"></i> Faucet</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/shortlinks" class="sidebar-link"><i class="fa-solid fa-link"></i> Shorterlink</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/views/iframe" class="sidebar-link"><i class="fa-solid fa-eye"></i> PTC</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/games" class="sidebar-link"><i class="fa-solid fa-dice"></i> Paid Games</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#campaigns" aria-expanded="false" aria-controls="campaigns">
                            <i class="fa-solid fa-sliders"></i>
                            Advertise
                        </a>
                        <ul id="campaigns" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#campaigns" style="">
                            <li class="sidebar-item">
                                <a href="/advertiser/deposit" class="sidebar-link"><i class="fa-solid fa-money-bill-transfer"></i> Deposit</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#task-multi" aria-expanded="false" aria-controls="multi-two">
                                    Micro Task
                                </a>

                                <ul id="task-multi" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="/advertiser/create-new-task" class="sidebar-link"><i class="fa-solid fa-file-circle-plus"></i> Create new campaign</a>
                                    </li>
                                    {{-- <li class="sidebar-item">
                                        <a href="pendding-task.html" class="sidebar-link"><i class="fa-solid fa-file-lines"></i> Pendding task</a>
                                    </li> --}}
                                    {{-- <li class="sidebar-item">
                                        <a href="/advertiser/campaigns" class="sidebar-link"><i class="fa-solid fa-satellite-dish"></i> Live campaigns</a>
                                    </li> --}}
                                    <li class="sidebar-item">
                                        <a href="/advertiser/campaigns" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> Manage Campaigns</a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="/advertiser/disputes" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> Disputes</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#ptc-multi" aria-expanded="false" aria-controls="multi-two">
                                    PTC ads
                                </a>
                                <ul id="ptc-multi" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="/advertiser/create-new-ptc-campaign" class="sidebar-link"><i class="fa-solid fa-plus"></i> New PTC campaign</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="/ptc-campaigns-list" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> PTC campaigns history</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="/withdraw" class="sidebar-link"><i class="fa-solid fa-money-bill-trend-up"></i> Withdraw</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/referral" class="sidebar-link"><i class="fa-solid fa-people-group"></i> Referrals</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="bonus.html" class="sidebar-link"><i class="fa-solid fa-gift"></i> Bonus</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/leaderboard" class="sidebar-link"><i class="fa-solid fa-people-roof"></i> Leaderboard</a>
                    </li>


                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#history" aria-expanded="false" aria-controls="history">
                            <i class="fa-solid fa-sliders"></i>
                            History
                        </a>
                        <ul id="history" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#history" style="">
                            <li class="sidebar-item">
                                <a href="/history/jobs" class="sidebar-link"> Jobs History</a>
                            </li>

                            <li class="sidebar-item">
                                <a href="/history/offers-and-surveys" class="sidebar-link">Offers and Surveys History</a>
                            </li>

                            
                            {{-- just do what did at iframe and windows and youtube page for the shortlinks and ptc --}}

                            <li class="sidebar-item">
                                <a href="/withdraw" class="sidebar-link">Payouts History</a>
                            </li>
                        </ul>
                    </li>


                    <li class="sidebar-header">
                        profile and settings
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi"
                            aria-expanded="false" aria-controls="multi">
                            <i class="fa-solid fa-gear"></i>
                            Settings
                        </a>
                        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="profile.html" class="sidebar-link"><i class="fa-solid fa-user"></i> Profile</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/profile/security" class="sidebar-link"><i class="fa-solid fa-shield-halved"></i> Security</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="profile-billing.html" class="sidebar-link"><i class="fa-solid fa-wallet"></i> Withdraw</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="profile-notification.html" class="sidebar-link"><i class="fa-solid fa-bell"></i> Notifications</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-item">
                        <a href="contact.html" class="sidebar-link"><i class="fa-solid fa-headset"></i> Contact/Support</a>
                    </li>
                    <li class="sidebar-item">
                      <a href="logout" class="sidebar-link"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
                    </li>
                    
                </ul>
            </div>
        </aside>
        <!-- Main Component -->
        <div id="main" class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
              <div class="container-fluid">
                <div class="navbar-header">
                    <button onclick="toggleSidebar()" class="btn" type="button" data-bs-theme="light">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <div class="notification-icon">
                        <a href="#" class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <!-- Use the Bootstrap Badge component for notifications -->
                            <span class="badge badge-danger">3</span>
                        </a>
                        <!-- Notification dropdown menu -->
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Notification 1</a>
                            <a class="dropdown-item" href="#">Notification 2</a>
                            <a class="dropdown-item" href="#">Notification 3</a>
                        </div>
                    </div>
                </ul>
              </div>
            </nav>
            <main class="content py-2">
                @yield('content')
            </main> 
        </div>
    </div>
    <footer >
        <div class="row justify-content-center mt-0 pt-0 row-1 mb-0  px-sm-3 px-2 footer" id="footer">
            <div class="row footer-bottom-area" style="border-top: 1px solid;">
                <div class="col-sm-6 col-auto">
                  <p>Copyright © 2023 <a href="#">Microtask</a> Company. All rights reserved.
                </div>
                <div class="col-md-3 col-auto"></div>
                <div class="col-md-3  my-auto text-right text-center">
                <small> <a class="email-address-font" href="#">contact@handbucks.com </a>|
                <a href="/social/facebook" target="_blank" title="Visit us on Facebook">
                  <i class="fab fa-facebook-square"></i>
                </a> 
                <a href="/social/telegram" target="_blank" title="Visit us on Telegram">
                  <i class="fab fa-telegram "></i>
                </a>
                <a href="/social/twitter" target="_blank" title="Visit us on Twitter">
                  <i class="fab fa-twitter-square"></i>
                </a>
                <a href="/social/instagram" target="_blank" title="Visit us on Instagram">
                  <i class="fab fa-instagram-square"></i>
                </a>
              </small>  
                </div> 
            </div>
        </div>
    </footer>


<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/js/splide.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    
    <script>
            var splide1 = new Splide('.splide1', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide1.mount();
        </script>
        <script>
            var splide2 = new Splide('.splide2', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide2.mount();
        </script>
        <script>
            var splide3 = new Splide('.splide3', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide3.mount();
        </script>
        <script>
            var splide4 = new Splide('.splide4', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide4.mount();
        </script>
        <script>
            var splide5 = new Splide('.splide5', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide5.mount();
        </script>
        <script>
            var splide6 = new Splide('.splide6', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide6.mount();
        </script>
        <script>
            var splide7 = new Splide('.splide7', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide7.mount();
        </script>
        <script>
            var splide8 = new Splide('.splide8', {
                type: 'loop',
                perPage: 3,
                rewind: true,
                breakpoints: {
                    640: {
                        perPage: 2,
                        gap: '.7rem',
                        height: '12rem',
                    },
                    480: {
                        perPage: 1,
                        gap: '.7rem',
                        height: '12rem',
                    },
                },
            });
            splide8.mount();
        </script>

        @if(session()->has('success'))
        <script>
            Swal.fire({
                title: "Good job!",
                text: "{{ session('success')}} ",
                icon: "success"
            });
        </script>
        @endif 

        @if(session()->has('error'))
            <script>
                Swal.fire({
                    title: "Oops!",
                    text: "{{ session('error')}} ",
                    icon: "error"
                });
            </script>
        @endif 
        
        @livewireScripts
       <script src="https://cdn.jsdelivr.net/npm/alpinejs"></script>
        <!-- Bootstrap and other scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  
        <script>
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
            const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
        </script>
    
   </body>
</html>
