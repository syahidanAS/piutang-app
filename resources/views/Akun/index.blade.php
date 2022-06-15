@extends('main')

@section('title', 'Data Akun')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Data Akun</h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')


<div class="content mt-3">
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

                    <div class="input-group ml-3">
                        <input type="text" class="form-control" placeholder="Cari Akun" aria-label="Recipient's username" aria-describedby="basic-addon2" id="akun-name" onkeyup="search()">
                        <div class="input-group-append" id="basic-addon2">
                            <button href="" class="btn btn-info" onclick="search()">
                                <i class="fa fa-search"></i>
                            </button>

                            <button type="button" class="btn btn-danger" id="clear-search" onclick="reset()">
                                <i class="fa fa-ban"></i> Reset
                            </button>
                        </div>
                    </div>

                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-add-akun">
                        <i class="fa fa-plus"></i> Tambah Data
                    </button>
                </div>
                <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">
                    <table class="table table-bordered table-hover  table-sm" id="akun-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th class="text-center" colspan="3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($akun as $item)
                            <tr id="row-data">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_akun }}</td>
                                <td>{{ $item->nama_akun }}</td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-akun" data-id="{{ $item->id }}" data-no-akun="{{ $item->no_akun }}" data-nama-akun="{{ $item->nama_akun }}" data-state="ubah">
                                        Ubah
                                    </button>
                                </td>
                                <td style="width: 25px;">
                                    <form action="{{ url('/akun/'.$item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus {{ $item->nama_akun }}?')">Hapus</button>
                                    </form>
                                </td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-akun" data-id="{{ $item->id }}" data-no-akun="{{ $item->no_akun }}" data-nama-akun="{{ $item->nama_akun }}" data-state="detail">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- MODAL UBAH AKUN -->
    <div class="modal fade" id="modal-edit-akun" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judul"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/update-akun" method="POST">
                        <div id="isiModal">

                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH AKUN -->
<div class="modal fade" id="modal-add-akun" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Data Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/akun" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="no_akun" class=" form-control-label">Kode Akun</label>
                        <input type="text" id="noAkun" class="form-control" name="no_akun" onkeyup="formatAdd()" maxlength="7" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_akun" class=" form-control-label">Nama Akun</label>
                        <input type="nama_akun" id="nama_akun" class="form-control" name="nama_akun" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        var url = '{{ url("") }}'

        $(document).ready(function() {
            $(document).on('click', '#edit', function() {
                let id = $(this).data('id');
                let no_akun = $(this).data('no-akun');
                let nama_akun = $(this).data('nama-akun');
                let state = $(this).data('state');

                if(state == "ubah"){
                    $("#judul").empty();
                    $("#judul").append(`Ubah Data`);
                    $("#isiModal").empty();
                    $("#isiModal").append(`
                    @csrf
                        <div class="form-group">
                            <input type="text" id="id" class="form-control" name="id" value="${id}" hidden>
                        </div>

                        <div class="form-group">
                            <label for="no_akun" class=" form-control-label">Kode Akun</label>
                            <input type="text" id="no_akun" class="form-control" name="no_akun" onkeyup="format()" maxlength="7" value="${no_akun}" required>
                        </div>

                        <div class="form-group">
                            <label for="nama_akun" class=" form-control-label">Nama Akun</label>
                            <input type="nama_akun" id="nama-akun" class="form-control" name="nama_akun" value="${nama_akun}" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>
                    `);
                }else{
                    $("#judul").empty();
                    $("#judul").append(`Detail Akun`);
                    $("#isiModal").empty();
                    $("#isiModal").append(`
                    @csrf
                        <div class="form-group">
                            <label for="no_akun" class=" form-control-label">Kode Akun</label>
                            <input type="text" id="no_akun" class="form-control" name="no_akun" onkeyup="format()" maxlength="7" value="${no_akun}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nama_akun" class=" form-control-label">Nama Akun</label>
                            <input type="nama_akun" id="nama-akun" class="form-control" name="nama_akun" value="${nama_akun}" readonly>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    `);
                }
            })
        })

        function reset() {
            document.getElementById('akun-name').value = '';
            $(document).ready(function() {
                let akunName = document.getElementById("akun-name").value;
                $.get(url + '/akun-api/?nama_akun=' + akunName, function(response) {
                    buildTable(response);
                });

            })
        }

        function search(){
            $(document).ready(function() {
                let akunName = document.getElementById("akun-name").value;
                $.get(url + '/akun-api/?nama_akun=' + akunName, function(response) {
                    buildTable(response);
                });

            })
        }
        // $(document).ready(function() {
        //     $(document).on('click', '#basic-addon2', function() {
        //         let ankunName = document.getElementById("akun-name").value;
        //         $.get(url + '/akun-api/?nama_Akun=' + ankunName, function(response) {
        //             buildTable(response);
        //         });
        //     })
        // })

        function buildTable(response) {
            var table = document.getElementById('akun-table')
            $('#akun-table tbody').empty();
            for (var i = 0; i < response.length; i++) {
                var row = `
                    <td>${i+1}</td>
                    <td>${response[i].no_akun}</td>
                    <td>${response[i].nama_akun}</td>
                    <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-akun" data-id="${response[i].id}" data-no-akun="${response[i].no_akun}" data-nama-akun="${response[i].nama_akun}" data-state="ubah">
                                        Ubah
                                    </button>
                                </td>
                                <td style="width: 25px;">
                                    <form action="${url + '/akun/' + response[i].id}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus ${response[i].nama_akun}?')">Hapus</button>
                                    </form>
                                </td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-akun" data-id="${response[i].id}" data-no-akun="${response[i].no_akun}" data-nama-akun="${response[i].nama_akun}" data-state="detail">
                                        Detail
                                    </button>
                                </td>
                    `
                table.innerHTML += row

            }
        }

        function format() {
            let number = document.getElementById("no_akun").value;
            let result = number.toString().replace(/\B(?=(\d{1})+(?!\d))/g, ".");
            document.getElementById("no_akun").value = result;
        }

        function formatAdd(){
            let number = document.getElementById("noAkun").value;
            let result = number.toString().replace(/\B(?=(\d{1})+(?!\d))/g, ".");
            document.getElementById("noAkun").value = result;
        }


    </script>
    @endsection
