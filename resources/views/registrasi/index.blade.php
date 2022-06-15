<!doctype html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }} - PIUTANG RSKP</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="{{ asset('style/assets/css/normalize.css') }} ">
    <link rel="stylesheet" href="{{ asset('style/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/scss/style.css') }}">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container" style="margin-top: 5%;">
            <div class="login-content">
                @if (session()->has('failed'))
                <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                    {{ session('failed') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="login-logo">
                </div>
                <div class="login-form">
                    <form action="/registrasi" method="POST">
                        @csrf
                        {{-- <div class="form-group">
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
                            @error('nama')
                            <p class="text-danger" style="font-size: 0.8rem;">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <div class="form-group">
                            <input type="number" name="nip" class="form-control @error('nip') is-invalid @enderror" placeholder="NIP" value="{{ old('nip') }}" pattern="\d*" maxlength="13" required>
                            @error('nip')
                            <p class="text-danger" style="font-size: 0.8rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Alamat Email" value="{{ old('email') }}" required>
                            @error('email')
                            <p class="text-danger" style="font-size: 0.8rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="number" name="no_tlp" class="form-control @error('no_tlp') is-invalid @enderror" placeholder="No Telepon" value="{{ old('no_tlp') }}" required>
                            @error('no_tlp')
                            <p class="text-danger" style="font-size: 0.8rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Username" value="{{ old('username') }}" required>
                            @error('username')
                            <p class="text-danger" style="font-size: 0.8rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="Password" required>
                            @error('password')
                            <p class="text-danger" style="font-size: 0.8rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="checkbox">
                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Daftar</button>
                    </form>
                    <a href="{{ url('/') }}">
                        <p class="text-info">Sudah memiliki akun, masuk?</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('style/assets/js/vendor/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('style/assets/js/main.js') }}"></script>


</body>

</html>
