<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Базові мета-дані з твого app layout --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Quinte Bootstrap 4 Admin Template">

    <title>@yield('title', 'Smarto Agency - Admin')</title>

    {{-- Laravel Vite (як у твоєму файлі). Якщо буде конфлікт з Quinte, можна прибрати. --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Quinte CSS (з public/assets/...) --}}
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Custom Quinte CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/et-line.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flag-icon.min.css') }}">

    <!-- Metis Menu / Slicknav -->
    <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}">

    <!-- Charts -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/am-charts/css/am-charts.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/charts/morris-bundle/morris.css') }}">

    <!-- Google Font (як у Quinte) -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
          rel="stylesheet">

    {{-- Твої додаткові бібліотеки (як в оригінальному layout) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>

    {{-- Modernizr з Quinte --}}
    <script src="{{ asset('assets/js/modernizr-2.8.3.min.js') }}"></script>

    {{-- Додатковий head з дочірніх вьюх --}}
    @yield('head')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">
    You are using an <strong>outdated</strong> browser.
    Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.
</p>
<![endif]-->

<div id="app">

    <!--=========================*
             Page Container
    *===========================-->
    <div class="page-container">

        <!--=========================*
                 Side Bar Menu
        *===========================-->
        <div class="sidebar-menu light-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    {{-- Тут можна вставити твій SVG-логотип, якщо хочеш --}}
                    <a href="{{ route('admin.dashboard') }}">
                        <svg width="104" height="26" viewBox="0 0 104 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.00675 6.89969C4.00675 7.55465 4.8377 7.81075 7.87754 7.81075C11.2243 7.81075 11.646 7.59243 11.646 7.18098C11.646 6.74434 11.4164 6.52602 7.39317 6.39797C1.96488 6.21744 0.520119 5.31897 0.520119 3.3289C0.520119 1.20868 2.43673 0.104492 7.62283 0.104492C13.1409 0.104492 14.8779 0.797236 14.8779 3.03291C14.8779 3.22604 14.8779 3.41917 14.8654 3.63749C14.4834 3.6123 13.8048 3.5997 13.1534 3.5997C12.477 3.5997 11.7984 3.6123 11.4164 3.63749V3.39398C11.4164 2.73902 10.9571 2.5207 7.57064 2.5207C4.45355 2.5207 3.99423 2.71383 3.99423 3.09799C3.99423 3.48215 4.23641 3.65009 8.51641 3.8684C13.5877 4.12451 15.1452 4.86973 15.1452 7.02774C15.1452 9.27601 13.3706 10.355 7.91512 10.355C2.34486 10.355 0.442871 9.41666 0.442871 7.46438C0.442871 7.23346 0.455398 6.98995 0.495066 6.73175C0.941856 6.78213 1.61831 6.82202 2.25717 6.82202C2.90857 6.82202 3.52238 6.80942 4.00675 6.78423V6.89969Z" fill="currentColor"/>
                            <path d="M31.9038 10.0463C31.9539 8.67133 31.9935 7.33622 32.0061 5.74291C32.0061 5.08796 32.0583 4.38052 32.0958 3.70037L32.0061 3.67518C31.6866 4.31754 31.3296 4.98509 30.8953 5.54979L27.5235 10.0442H25.774L21.993 4.983C21.5712 4.4183 21.2017 3.75075 20.8823 3.13358L20.7925 3.15877C20.8301 3.82632 20.8822 4.49388 20.8948 5.2265C20.9073 7.02554 20.947 8.52649 21.0221 10.0421H17.5981C17.6754 8.65454 17.7004 7.25435 17.7004 5.21391C17.7004 3.10839 17.6754 1.66832 17.6357 0.410889H22.7717C23.4753 1.4521 24.5213 3.01812 25.8366 4.93261C26.1811 5.42173 26.5527 5.97383 26.9097 6.53852H26.987C27.3315 5.96123 27.6634 5.40914 27.9829 4.93261C29.2084 3.09579 30.3588 1.42691 31.0478 0.410889H35.5825C35.5449 1.67042 35.5052 3.10839 35.5052 5.21391C35.5052 7.25645 35.5177 8.65664 35.6075 10.0421H31.9038V10.0463Z" fill="currentColor"/>
                            <path d="M50.3581 10.0464C50.2433 9.76303 49.9364 9.13536 49.5146 8.35025C48.1868 8.33765 46.8443 8.33765 45.8234 8.33765C44.8901 8.33765 43.5247 8.33765 42.2345 8.35025C41.8002 9.08288 41.4557 9.69795 41.2887 10.0464H37.0859C38.9128 7.50426 42.4516 2.10926 43.2804 0.413086H48.5939C49.4624 2.10926 53.0013 7.43919 54.9033 10.0464H50.3581ZM45.0321 3.74036C44.5979 4.4331 44.0989 5.2434 43.6145 6.0516C44.4183 6.06419 45.2242 6.0642 45.838 6.0642C46.5019 6.0642 47.3705 6.06419 48.239 6.0516C47.7671 5.2287 47.2682 4.40791 46.8214 3.71306C46.4247 3.11059 46.1449 2.60887 45.9278 2.21002L45.0321 3.74036Z" fill="currentColor"/>
                            <path d="M67.2673 6.10403C69.5033 6.32235 70.5243 6.68132 70.9335 8.14657C71.1631 8.98206 71.3678 9.67481 71.5348 10.0485H68.3551C67.7809 10.0485 67.6786 9.92041 67.3592 8.66088C67.0773 7.5315 66.6556 7.32578 64.3318 7.32578H59.7595C59.7846 8.41737 59.8368 9.35573 59.912 10.0485H56.2583C56.2834 8.64829 56.3084 7.30059 56.3084 5.23285C56.3084 3.19031 56.2959 1.80272 56.2583 0.417236H65.762C69.2236 0.417236 70.8458 1.3283 70.8458 3.28058C70.8458 4.93686 69.7727 5.78495 67.2694 6.05575V6.10403H67.2673ZM64.6492 4.92217C66.7057 4.92217 67.4468 4.5758 67.4468 3.86836C67.4468 3.13573 66.7182 2.89222 64.6868 2.89222C63.7159 2.89222 60.9955 2.90481 59.7053 2.9447C59.7053 3.59966 59.7053 4.25461 59.7178 4.88438C60.6385 4.92217 62.5927 4.92217 64.6492 4.92217Z" fill="currentColor"/>
                            <path d="M85.8759 0.413086C85.8509 0.786748 85.8509 1.35144 85.8509 1.85106C85.8509 2.34017 85.8509 2.94265 85.8759 3.3541C84.1013 3.30372 82.3371 3.30372 80.8046 3.30372C80.8046 6.52813 80.8172 9.31379 80.8548 10.059C80.3829 10.0212 79.5895 10.0212 79.053 10.0212C78.5164 10.0212 77.7627 10.0212 77.2512 10.059C77.2888 9.31379 77.3159 6.54072 77.3159 3.30372C75.7835 3.30372 74.0193 3.30372 72.2446 3.3541C72.2572 2.94265 72.2572 2.35277 72.2572 1.85106C72.2572 1.34934 72.2572 0.797244 72.2446 0.413086H85.8759Z" fill="currentColor"/>
                            <path d="M8.5414 25.8936C2.58908 25.8936 0.148438 23.7608 0.148438 20.614C0.148438 17.3392 2.84379 15.387 8.5414 15.387C14.239 15.387 16.9344 17.3392 16.9344 20.614C16.9344 23.7608 14.4937 25.8936 8.5414 25.8936ZM13.3454 20.6287C13.3454 19.048 12.1074 18.1747 8.5414 18.1747C4.97753 18.1747 3.73737 19.048 3.73737 20.6287C3.73737 22.1821 4.96291 23.1079 8.5414 23.1079C12.1324 23.1058 13.3454 22.1821 13.3454 20.6287Z" fill="currentColor"/>
                            <path d="M30.2419 21.5144C32.478 21.7328 33.4989 22.0917 33.9081 23.557C34.1378 24.3925 34.3424 25.0852 34.5094 25.4589H31.3297C30.7555 25.4589 30.6532 25.3308 30.3338 24.0713C30.0519 22.9419 29.6302 22.7362 27.3065 22.7362H22.7342C22.7592 23.8278 22.8114 24.7661 22.8866 25.4589H19.2329C19.258 24.0587 19.283 22.711 19.283 20.6433C19.283 18.6007 19.2705 17.2131 19.2329 15.8276H28.7366C32.1982 15.8276 33.8204 16.7387 33.8204 18.691C33.8204 20.3473 32.7473 21.1954 30.244 21.4662V21.5144H30.2419ZM27.6238 20.3326C29.6803 20.3326 30.4215 19.9862 30.4215 19.2788C30.4215 18.5461 29.6928 18.3026 27.6614 18.3026C26.6906 18.3026 23.9702 18.3152 22.6799 18.3551C22.6799 19.0101 22.6799 19.665 22.6924 20.2948C23.6111 20.3326 25.5673 20.3326 27.6238 20.3326Z" fill="currentColor"/>
                            <path d="M48.1223 25.4568C48.0074 25.1734 47.7005 24.5458 47.2788 23.7606C45.9509 23.7481 44.6085 23.7481 43.5876 23.7481C42.6543 23.7481 41.2889 23.7481 39.9986 23.7606C39.5644 24.4933 39.2199 25.1083 39.0528 25.4568H34.8501C36.6769 22.9147 40.2158 17.5197 41.0446 15.8235H46.3581C47.2266 17.5197 50.7654 22.8496 52.6674 25.4568H48.1223ZM42.7963 19.1508C42.362 19.8435 41.863 20.6538 41.3787 21.462C42.1825 21.4746 42.9884 21.4746 43.6022 21.4746C44.2661 21.4746 45.1346 21.4746 46.0031 21.462C45.5313 20.6391 45.0323 19.8183 44.5855 19.1235C44.1888 18.521 43.9091 18.0193 43.6919 17.6204L42.7963 19.1508Z" fill="currentColor"/>
                            <path d="M69.4008 15.8234C69.3632 17.0052 69.3507 18.5839 69.3507 20.6264C69.3507 22.7466 69.3507 24.172 69.3758 25.4546H65.7346C64.9809 24.5939 61.0078 21.8712 58.8115 20.3304C58.2749 19.9442 57.7258 19.56 57.279 19.1486C57.3291 19.919 57.3437 20.6894 57.3688 21.4724C57.3813 23.4624 57.3939 24.8626 57.4335 25.4546H54.1494C54.1494 24.1573 54.187 22.6941 54.187 20.639C54.187 18.5965 54.187 17.1333 54.1494 15.8234H58.3146C58.7864 16.4406 63.1687 19.5747 65.1229 20.9224C65.5822 21.231 66.0436 21.4745 66.3881 21.8083C66.338 21.1029 66.3109 20.4207 66.3109 19.8938C66.3109 18.1094 66.2733 16.5035 66.2211 15.8213H69.4008V15.8234Z" fill="currentColor"/>
                            <path d="M80.2051 25.7654C74.8269 25.7654 71.6973 24.212 71.6973 20.5761C71.6973 16.7492 74.9542 15.5149 80.013 15.5149C84.8421 15.5149 87.8297 16.3105 87.8297 19.1885C87.8297 19.2788 87.8297 19.3817 87.8172 19.4719C87.4351 19.4467 86.7566 19.4341 86.1052 19.4341C85.4037 19.4341 84.7377 19.4467 84.3431 19.4719C84.3431 19.4194 84.3556 19.3691 84.3556 19.3187C84.3556 18.4202 83.1426 18.1368 80.0005 18.1368C76.6788 18.1368 75.3008 18.817 75.3008 20.6412C75.3008 22.2975 76.5911 23.1833 80.2051 23.1833C82.9004 23.1833 84.0884 22.7719 84.4329 21.8986C82.6582 21.8986 80.2677 21.9112 78.6852 21.9511C78.6977 21.758 78.6977 21.3465 78.6977 21.1555C78.6977 20.8091 78.6977 20.5005 78.6852 20.257C80.7542 20.2948 82.8754 20.2822 84.4203 20.2822C85.3661 20.2822 86.5791 20.2948 87.8443 20.257C88.074 23.7228 87.2055 25.7654 80.2051 25.7654Z" fill="currentColor"/>
                            <path d="M104 15.8235C103.987 16.1069 103.975 16.5561 103.975 16.9529C103.975 17.3643 103.975 17.8261 104 18.0969C101.342 18.0843 96.5402 18.0718 94.1517 18.0718V19.5496C96.9118 19.5496 101.726 19.5496 103.86 19.5118C103.848 20.0135 103.835 21.0401 103.848 21.5796C101.753 21.5544 96.8847 21.5292 94.1517 21.5292V23.0322C96.5402 23.0322 101.509 23.0196 104 23.007C103.975 23.3156 103.975 23.8026 103.975 24.254C103.975 24.6654 103.975 25.1398 104 25.461H90.498C90.5502 24.1511 90.5502 22.7005 90.5502 20.6454C90.5502 18.6029 90.5502 17.125 90.498 15.8298H104V15.8235Z" fill="currentColor"/>
                        </svg>

                    </a>
                </div>
            </div>

            <div class="main-menu">
                <div class="menu-inner" id="sidebar_menu">
                    <nav>
                        <ul class="metismenu" id="menu">
                            @auth
                                {{-- Меню для адміністратора --}}
                                @if(Auth::user()->role === 'admin')
                                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('admin.dashboard') }}">
                                            <i class="feather ft-home"></i>
                                            <span>📊 Дашборд</span>
                                        </a>
                                    </li>

                                    <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.users.index') }}">
                                            <i class="feather ft-users"></i>
                                            <span>👥 Користувачі</span>
                                        </a>
                                    </li>

                                    <li class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.projects.index') }}">
                                            <i class="feather ft-layers"></i>
                                            <span>🏗️ Проєкти</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Меню для клієнта --}}
                                @if(Auth::user()->role === 'client')
                                    <li>
                                        <a href="javascript:void(0)">
                                            <i class="feather ft-home"></i>
                                            <span class="text-muted">🏠 Дашборд (soon)</span>
                                        </a>
                                    </li>

                                    <li class="{{ request()->routeIs('client.projects.*') ? 'active' : '' }}">
                                        <a href="{{ route('client.projects.index') }}">
                                            <i class="feather ft-briefcase"></i>
                                            <span>🏗️ Мої проєкти</span>
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!--=========================*
               End Side Bar Menu
        *===========================-->

        <!--==================================*
                   Main Content Section
        *====================================-->
        <div class="main-content page-content">

            <!--==================================*
                         Header Section
            *====================================-->
            <div class="header-area">
                <div class="row align-items-center">
                    <div class="mobile-logo d_none_lg">
                        <a href="{{ route('admin.dashboard') }}">
                            <img src="{{ asset('assets/images/mobile-logo.png') }}" alt="logo">
                        </a>
                    </div>

                    {{-- Навігація + пошук (ліворуч) --}}
                    <div class="col-md-6 d_none_sm d-flex align-items-center">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="search-box pull-left">
                            <form action="#">
                                <i class="ti-search"></i>
                                <input type="text" name="search" placeholder="Search..." required>
                            </form>
                        </div>
                    </div>

                    {{-- Правий блок: мова, нотифікації, юзер --}}
                    <div class="col-md-6 col-sm-12">
                        <ul class="notification-area pull-right">
                            <li>
                                <span class="nav-btn pull-left d_none_lg">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            </li>

                            {{-- Перемикач мови (твоя логіка) --}}
                            <li class="user-dropdown">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button"
                                            id="langDropdown"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('admin.Lang') }} - {{ strtoupper(App::currentLocale()) }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="langDropdown">
                                        <a class="dropdown-item @if(App::currentLocale()=='ua') active @endif"
                                           href="{{ url('/locale/ua') }}">
                                            UA
                                        </a>
                                        <a class="dropdown-item @if(App::currentLocale()=='en') active @endif"
                                           href="{{ url('/locale/en') }}">
                                            EN
                                        </a>
                                    </div>
                                </div>
                            </li>

                            {{-- Приклади іконок Quinte (можеш прибрати, якщо не треба) --}}
                            <li id="full-view" class="d_none_sm"><i class="feather ft-maximize"></i></li>
                            <li id="full-view-exit" class="d_none_sm"><i class="feather ft-minimize"></i></li>

                            {{-- Нотифікації / повідомлення можна залишити як демо --}}
                            <li class="dropdown d_none_sm">
                                <i class="ti-bell dropdown-toggle" data-toggle="dropdown"><span></span></i>
                                <div class="dropdown-menu bell-notify-box notify-box">
                                    <span class="notify-title">No new notifications</span>
                                </div>
                            </li>

                            {{-- User dropdown з твоєю логікою auth/guest --}}
                            @guest
                                @if (Route::has('login'))
                                    <li>
                                        <a class="btn btn-sm btn-outline-light"
                                           href="{{ route('login') }}">@lang('Login')</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="ml-2">
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('register') }}">@lang('Register')</a>
                                    </li>
                                @endif
                            @else
                                <li class="user-dropdown">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button"
                                                id="userDropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="d_none_sm">
                                                {{ Auth::user()->name }}
                                                <i class="ti-angle-down"></i>
                                            </span>
                                            <img src="{{ asset('assets/images/user.jpg') }}" alt="" class="img-fluid">
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="userDropdownMenuButton">
                                            <a class="dropdown-item" href="#">
                                                <i class="ti-user"></i> Profile
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="ti-settings"></i> Account Settings
                                            </a>
                                            <span role="separator" class="divider"></span>
                                            <a class="dropdown-item"
                                               href="{{ route('logout') }}"
                                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                <i class="ti-power-off"></i> @lang('Logout')
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endguest

                            <li class="settings-btn d_none_sm">
                                <i class="ti-more"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--==================================*
                     End Header Section
            *====================================-->

            <!--==================================*
                     Main Section (твій контент)
            *====================================-->
            <div class="main-content-inner">
                @yield('content')
            </div>
            <!--==================================*
                     End Main Section
            *====================================-->
        </div>
        <!--=================================*
               End Main Content Section
        *===================================-->

        <!--=================================*
                      Footer
        *===================================-->
        <footer>
            <div class="footer-area">
                <p>&copy; {{ date('Y') }}. All rights reserved. Smarto Agency.</p>
            </div>
        </footer>
        <!--=================================*
                    End Footer
        *===================================-->

    </div>
    <!--=========================*
            End Page Container
    *===========================-->

    <!--=========================*
          Offset Sidebar Menu
    *===========================-->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
            <li><a data-toggle="tab" href="#settings">Settings</a></li>
        </ul>
        <div class="offset-content tab-content">
            <div id="activity" class="tab-pane fade in show active">
                <div class="recent-activity">
                    {{-- Тут можна залишити демо-блоки Quinte або очистити --}}
                    <p class="p-3 mb-0">No recent activity.</p>
                </div>
            </div>
            <div id="settings" class="tab-pane fade">
                <div class="offset-settings">
                    <h4>General Settings</h4>
                    {{-- Демо-перемикачі можна забрати/змінити --}}
                </div>
            </div>
        </div>
    </div>
    <!--================================*
             End Offset Sidebar Menu
    *==================================-->

</div> {{-- /#app --}}

{{-- =========================
          Scripts
   ========================= --}}

<!-- jQuery (один раз, перед усіма плагінами) -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<!-- Bootstrap 4 bundle -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<!-- Owl Carousel -->
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

<!-- Metis Menu -->
<script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>

<!-- SlimScroll (щоб не було "$(...).slimScroll is not a function") -->
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

<!-- Slick Nav -->
<script src="{{ asset('assets/js/jquery.slicknav.min.js') }}"></script>

<!-- AM Charts -->
<script src="{{ asset('assets/vendors/am-charts/js/ammap.js') }}"></script>
<script src="{{ asset('assets/vendors/am-charts/js/worldLow.js') }}"></script>
<script src="{{ asset('assets/vendors/am-charts/js/continentsLow.js') }}"></script>
<script src="{{ asset('assets/vendors/am-charts/js/light.js') }}"></script>
<script src="{{ asset('assets/js/am-maps.js') }}"></script>

<!-- Morris Charts -->
<script src="{{ asset('assets/vendors/charts/morris-bundle/raphael.min.js') }}"></script>
<script src="{{ asset('assets/vendors/charts/morris-bundle/morris.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('assets/vendors/charts/charts-bundle/Chart.bundle.js') }}"></script>

<!-- Sparkline -->
<script src="{{ asset('assets/vendors/charts/sparkline/jquery.sparkline.js') }}"></script>

<!-- Home (Quinte demo) -->
<script src="{{ asset('assets/js/home.js') }}"></script>

<!-- Main JS (ініціалізація меню, скролів і т.д.) -->
<script src="{{ asset('assets/js/main.js') }}"></script>

{{-- Додаткові скрипти зі сторінок --}}
@yield('scripts')

</body>
</html>
