@extends('main')

@section('title', 'Jenis Pelayanan')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Data Jenis Pelayanan</h1>
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
                        <input type="text" class="form-control" placeholder="Cari kode, nama, unit" aria-label="Recipient's username" aria-describedby="basic-addon2" id="pelayanan-name" onkeyup="search()">
                        <div class="input-group-append" id="basic-addon2">
                            <button href="" class=" btn btn-info" >
                                <i class="fa fa-search"></i>
                            </button>

                            <button type="button" class="btn btn-danger" id="clear-search" onclick="reset()">
                                <i class="fa fa-ban"></i> Reset
                            </button>
                        </div>
                    </div>

                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-add-debitur">
                        <i class="fa fa-plus"></i> Tambah Data
                    </button>
                </div>
                <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">
                    <table class="table table-bordered table-hover  table-sm" id="service-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Layanan</th>
                                <th>Nama Pelayanan</th>
                                <th>Unit Layanan</th>
                                <th>Harga</th>
                                <th class="text-center" colspan="3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengobatan as $item)
                            <tr id="row-data">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kd_layanan }}</td>
                                <td>{{ $item->nama_pelayanan }}</td>
                                <td>{{ $item->unit_layanan }}</td>
                                <td>@currency($item->harga)</td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="{{ $item->id }}" data-kd-layanan="{{ $item->kd_layanan }}" data-nama-pelayanan="{{ $item->nama_pelayanan }}" data-unit-layanan="{{ $item->unit_layanan }}" data-harga="{{ $item->harga }}" data-state="ubah">
                                        Ubah
                                    </button>
                                </td>
                                <td style="width: 25px;">
                                    <form action="{{ url('/jenis-pengobatan/'.$item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus {{ $item->nama_pelayanan }}?')">Hapus</button>
                                    </form>
                                </td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="{{ $item->id }}" data-kd-layanan="{{ $item->kd_layanan }}" data-nama-pelayanan="{{ $item->nama_pelayanan }}" data-unit-layanan="{{ $item->unit_layanan }}" data-harga="{{ $item->harga }}" data-state="detail">
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



    <!-- MODAL UBAH DEBITUR -->
    <div class="modal fade" id="modal-edit-debitur" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judul"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/update-jenis-pengobatan" method="POST">
                        <div id="isiModal">

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH DEBITUR -->
<div class="modal fade" id="modal-add-debitur" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Data Jenis Pelayanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/jenis-pengobatan" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Kode Layanan</label>
                        <input type="text" id="kd_layanan" class="form-control" name="kd_layanan" required>
                    </div>
                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Nama Pelayanan</label>
                        <input type="text" id="nama_pelayanan" class="form-control" name="nama_pelayanan" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat" class=" form-control-label">Unit Layanan</label>
                        <input type="text" id="unit_layanan" class="form-control" name="unit_layanan" required>
                    </div>
                    <div class="form-group">
                        <label for="harga" class=" form-control-label">Harga</label>
                        <input type="number" id="harga" class="form-control" name="harga" required>
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

<script>
    function rupiah(angka){
   var reverse = angka.toString().split('').reverse().join(''),
   ribuan = reverse.match(/\d{1,3}/g);
   ribuan = ribuan.join('.').split('').reverse().join('');
   return ribuan;
 }
        var url = '{{ url("") }}'

        $(document).ready(function() {
            $(document).on('click', '#edit', function() {
                var id = $(this).data('id');
                var kd_layanan = $(this).data('kd-layanan');
                var nama_pelayanan = $(this).data('nama-pelayanan');
                var unit_layanan = $(this).data('unit-layanan');
                var harga = $(this).data('harga');
                var state = $(this).data('state');

                if(state == "ubah"){
                    $("#judul").empty();
                    $("#judul").append("Ubah Data");
                    $("#isiModal").empty();
                    $("#isiModal").append(`
                    @csrf
                        <div class="form-group">
                            <input type="text" id="id" class="form-control" name="id" value="${id}" hidden>
                        </div>

                        <div class="form-group">
                            <label for="kd_layanan" class=" form-control-label">Kode Pelayanan</label>
                            <input type="text" id="kd_layanan" class="form-control" name="kd_layanan" value="${kd_layanan}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pelayanan" class=" form-control-label">Nama Pelayanan</label>
                            <input id="nama_pelayanan" class="form-control" name="nama_pelayanan" value="${nama_pelayanan}" required>
                        </div>
                        <div class="form-group">
                            <label for="unit_layanan"  class=" form-control-label">Unit Layanan</label>
                            <input  id="unit_layanan" class="form-control" name="unit_layanan" value="${unit_layanan}" required>
                        </div>
                        <div class="form-group">
                            <label for="harga"  class=" form-control-label">Harga</label>
                            <input  id="harga" class="form-control" name="harga" value="${harga}" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>
                    `);
                }else{
                    $("#judul").empty();
                    $("#judul").append("Detail Jenis Pengobatan");
                    $("#isiModal").empty();
                    $("#isiModal").append(`
                    @csrf
                        <div class="form-group">
                            <label for="kd_layanan" class=" form-control-label">Kode Pelayanan</label>
                            <input type="text" id="kd_layanan" class="form-control" name="kd_layanan" value="${kd_layanan}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_pelayanan" class=" form-control-label">Nama Pelayanan</label>
                            <input id="nama_pelayanan" class="form-control" name="nama_pelayanan" value="${nama_pelayanan}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="unit_layanan"  class=" form-control-label">Unit Layanan</label>
                            <input  id="unit_layanan" class="form-control" name="unit_layanan" value="${unit_layanan}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="harga"  class=" form-control-label">Harga</label>
                            <input  id="harga" class="form-control" name="harga" value="${harga}" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    `);
                }

            })
        })

        function reset() {
            document.getElementById('pelayanan-name').value = '';
            $(document).ready(function() {
                let serviceName = document.getElementById("pelayanan-name").value;
                $.get(url + '/pengobatan-api/?nama_pelayanan=' + serviceName + '&kd_layanan=' + serviceName + '&unit_layanan=' + serviceName, function(response) {
                    buildTable(response);
                });
            })
        }

        function search(){
            $(document).ready(function() {
                let serviceName = document.getElementById("pelayanan-name").value;
                $.get(url + '/pengobatan-api/?nama_pelayanan=' + serviceName + '&kd_layanan=' + serviceName + '&unit_layanan=' + serviceName, function(response) {
                    buildTable(response);
                });
            })
        }


        function buildTable(response) {
            var table = document.getElementById('service-table')
            $('#service-table tbody').empty();
            for (var i = 0; i < response.length; i++) {
                var row = `
                    <td>${i+1}</td>
                    <td>${response[i].kd_layanan}</td>
                    <td>${response[i].nama_pelayanan}</td>
                    <td>${response[i].unit_layanan}</td>
                    <td>Rp. ${rupiah(response[i].harga)}</td>
                    <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-warning btn-sm text-warning" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="${response[i].id}" data-kd-layanan="${response[i].kd_layanan}" data-nama-pelayanan="${response[i].nama_pelayanan}" data-unit-layanan="${response[i].unit_layanan}" data-harga="${response[i].harga}" data-state="ubah">
                                        Ubah
                                    </button>
                                </td>
                                <td style="width: 25px;">
                                    <form action="${url + '/jenis-pengobatan/' + response[i].id}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger btn-sm " type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus ${response[i].nama_pelayanan}?')">Hapus</button>
                                    </form>
                                </td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-info btn-sm text-info" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="${response[i].id}" data-kd-layanan="${response[i].kd_layanan}" data-nama-pelayanan="${response[i].nama_pelayanan}" data-unit-layanan="${response[i].unit_layanan}" data-harga="${response[i].harga}" data-state="detail">
                                        Detail
                                    </button>
                                </td>
                    `
                table.innerHTML += row

            }
        }

    </script>
    @endsection
