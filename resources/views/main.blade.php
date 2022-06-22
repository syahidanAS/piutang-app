<!doctype html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') - SIP RSKP</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="{{ asset('style/assets/css/normalize.css') }} ">
    <link rel="stylesheet" href="{{ asset('style/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/scss/style.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment-with-locales.min.js" integrity="sha512-vFABRuf5oGUaztndx4KoAEUVQnOvAIFs59y4tO0DILGWhQiFnFHiR+ZJfxLDyJlXgeut9Z07Svuvm+1Jv89w5g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.js" integrity="sha512-rozBdNtS7jw9BlC76YF1FQGjz17qQ0J/Vu9ZCFIW374sEy4EZRbRcUZa2RU/MZ90X2mnLU56F75VfdToGV0RiA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script   script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>

    </style>
</head>

<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand mt-4" href="{{url('dashboard')}}"><img class="img-thumbnail" src="{{ asset('images/hospital-logo.png') }}" alt="Logo" style="width:65px; margin-bottom:20px;"></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse ">
                @if(auth()->user()->role== "admin")
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ url('/dashboard') }}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>

                    <h3 class="menu-title">Data Master</h3>
                    <li>
                        <a href="{{url('debitur')}}"> <i class="menu-icon fa fa-address-card"></i>Debitur</a>
                    </li>
                    <li>
                        <a href="{{url('jenis-pengobatan')}}"> <i class="menu-icon fa fa-medkit"></i>Jenis Pengobatan</a>
                    </li>
                    {{-- <li>
                        <a href="{{url('akun')}}"> <i class="menu-icon fa fa-money"></i>Data Akun</a>
                    </li> --}}
                    <li>
                        <a href="{{url('users')}}"> <i class="menu-icon fa fa-user"></i>Data User</a>
                    </li>

                    <h3 class="menu-title">Menu Utama</h3>
                    <li>
                        <a href="{{url('piutang')}}"> <i class="menu-icon fa fa-file-o"></i>Data Piutang</a>
                    </li>
                    {{-- <li>
                        <a href="{{url('edulevels')}}"> <i class="menu-icon fa fa-credit-card"></i>Tagihan</a>
                    </li> --}}
                    <li>
                        <a href="{{url('pembayaran')}}"> <i class="menu-icon fa fa-money"></i>Pembayaran</a>
                    </li>

                    <h3 class="menu-title">Laporan</h3>
                    <li>
                        <a href="{{url('rekap-piutang')}}"> <i class="menu-icon fa fa-paste"></i>Rekapitulasi Piutang</a>
                    </li>
                    <li>
                        <a href="{{url('/rekap-umur-piutang')}}"> <i class="menu-icon fa fa-file-text-o"></i>Rekapitulasi Umur Piutang</a>
                    </li>
                    <li>
                        <a href="{{url('/jurnal')}}"> <i class="menu-icon fa fa-file-text-o"></i>Jurnal Umum</a>
                    </li>
                </ul>
                @else
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ url('/dashboard') }}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <h3 class="menu-title">Laporan</h3>
                    <li>
                        <a href="{{url('edulevels')}}"> <i class="menu-icon fa fa-paste"></i>Rekapitulasi Piutang</a>
                    </li>
                </ul>
                @endif
            </div>
        </nav>
    </aside>
    <div id="right-panel" class="right-panel">
        <header id="header" class="header">
            <div class="header-menu">
                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa-arrow-circle-o-left"></i></a>

                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                            </svg>
                        </a>
                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="#"><i class="fa fa- user"></i>{{ auth()->user()->nama }}</a>
                            <form action="/logout" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm" type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                    <!--
                    <div class="language-select dropdown" id="language-select">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="language" aria-haspopup="true" aria-expanded="true">
                            <i class="flag-icon flag-icon-us"></i>
                        </a>
                    </div> -->

                </div>
            </div>
        </header>

        @yield('breadcrumbs')
        @yield('content')

    </div>


    <script src="{{ asset('style/assets/js/vendor/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('style/assets/js/main.js') }}"></script>


</body>

</html>
