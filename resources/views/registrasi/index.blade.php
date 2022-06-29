<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }} - PIUTANG RSKP</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"></head>

<body>

    <section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex align-items-center justify-content-center h-100">
      <div class="col-md-8 col-lg-7 col-xl-6">
        <img src="{{ asset('images/registration-ilustration.svg') }}"
          class="img-fluid" alt="Hospital image">
      </div>
      <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
        @if (session()->has('failed'))
        <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
            {{ session('failed') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <h5 class="pb-2" style="text-align: center">SISTEM PENGELOLAAN PIUTANG RUMAH SAKIT</h5>
        <form action="/registrasi" method="POST">
            @csrf
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

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary btn-md btn-block">Daftar</button>

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0 text-muted">ATAU</p>
          </div>
          <a class="btn btn-success btn-md btn-block" href="/login">Kembali ke halaman login?</a>
        </form>
      </div>
    </div>
  </div>
</section>
    {{-- END OF LOGIN SECTION --}}
    <style>
        *{
            background-color: rgb(247, 247, 247);
        }
        .divider:after,
        .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
        }
    </style>
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>

</html>
