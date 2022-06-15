@extends('main')

@section('title', 'Kelola User')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Data User</h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
@if (session()->has('success'))
<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session()->has('failed'))
<div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
    {{ session('failed') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="animated fadeIn">
    <div class="card">
        <div class="card-header">
            <div class="pull-left">

                {{-- <div class="input-group ml-3">
                    <input type="text" class="form-control" placeholder="Cari kode, nama, unit" aria-label="Recipient's username" aria-describedby="basic-addon2" id="pelayanan-name" onkeyup="search()">
                    <div class="input-group-append" id="basic-addon2">
                        <button href="" class=" btn btn-info" >
                            <i class="fa fa-search"></i>
                        </button>

                        <button type="button" class="btn btn-danger" id="clear-search" onclick="reset()">
                            <i class="fa fa-ban"></i> Reset
                        </button>
                    </div>
                </div> --}}

            </div>
            <div class="pull-left">
                <button type="button" class="btn btn-success " data-toggle="modal" data-target="#modal-add-user" style="margin-left: 19px">
                    <i class="fa fa-plus"></i> Tambah Data
                </button>
            </div>
            <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">
                <table class="table table-bordered table-hover  table-sm" id="service-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Status</th>
                            <th class="text-center" colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nip }}</td>
                            @if($item->password == '' && $item->username == '')
                            <td style="background-color:#facaca; color:#ca0505;">Belum Terdaftar</td>
                            @elseif($item->email == '' || $item->no_tlp == '')
                            <td style="background-color:#edfaca; color:#cbda03;">Belum Lengkap</td>
                            @else
                            <td  style="background-color: #ddfcdd; color:#05e809;">Sudah Terdaftar</td>
                            @endif
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-warning btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-user" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-email="{{ $item->email }}" data-nip="{{ $item->nip }}" data-no_tlp="{{ $item->no_tlp }}">
                                    Ubah
                                </button>
                            </td>
                            <td class="text-center">
                                <form action="{{ url('/user/'.$item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DEBITUR -->
<div class="modal fade" id="modal-add-user" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/user" method="POST">
                    @csrf
                    <p id="nama"></p>
                    <div class="form-group">
                        <label for="nip" class=" form-control-label">NIP</label>
                        <input type="text" id="nip" class="form-control" name="nip" required>
                    </div>
                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Nama Lengkap</label>
                        <input type="text" id="nama" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="role" class=" form-control-label">Role</label>
                       <select class="form-control" name="role" id="role" >
                           <option value="admin">Admin</option>
                           <option value="direktur">Direktur</option>
                       </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


  <!-- MODAL UBAH USER -->
  <div class="modal fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Ubah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/update-user" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="id" class="form-control" name="id" hidden>
                    </div>

                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Nama Lengkap</label>
                        <input type="text" id="nama-lengkap" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class=" form-control-label">Email</label>
                        <input type="email" id="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="nip" class=" form-control-label">NIP</label>
                        <input type="number" id="edit-nip" class="form-control" name="nip" required>
                    </div>
                    <div class="form-group">
                        <label for="no_tlp" class=" form-control-label">No. Telepon</label>
                        <input type="text" id="no_tlp" class="form-control" name="no_tlp" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
            $(document).ready(function() {
            $(document).on('click', '#edit', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var email = $(this).data('email');
                var nip = $(this).data('nip');
                var no_tlp = $(this).data('no_tlp');

                $('#id').val(id);
                $('#nama-lengkap').val(nama);
                $('#email').val(email);
                $('#edit-nip').val(nip);
                $('#no_tlp').val(no_tlp);
            })
        })
</script>
@endsection
