@extends('main')

@section('title', 'Debitur')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Data Debitur</h1>
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
                        <input type="text" class="form-control" placeholder="Cari Debitur" aria-label="Recipient's username" aria-describedby="basic-addon2" id="debitur-name" onkeyup="search()">
                        <div class="input-group-append" id="basic-addon2">
                            <button href="" class="btn btn-info" >
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
                    <table class="table table-bordered table-hover  table-sm" id="debt-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center" colspan="3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debiturs as $item)
                            
                            <tr id="row-data">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nm_debitur }}</td>
                                <td>{{ $item->email_deb }}</td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="{{ $item->id }}" data-nm_debitur="{{ $item->nm_debitur }}" data-email_deb="{{ $item->email_deb }}" data-alamat="{{ $item->alamat }}" data-tlp_deb="{{ $item->tlp_deb }}" data-state="ubah">
                                        Ubah
                                    </button>
                                </td>
                                <td style="width: 25px;">
                                    <form action="{{ url('/debitur/'.$item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus {{ $item->nm_debitur }}?')">Hapus</button>
                                    </form>
                                </td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="{{ $item->id }}" data-nm_debitur="{{ $item->nm_debitur }}" data-email_deb="{{ $item->email_deb }}" data-alamat="{{ $item->alamat }}" data-tlp_deb="{{ $item->tlp_deb }}" data-state="detail">
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
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Ubah Data Debitur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <form action="/update-debitur" method="POST">
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
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Data Debitur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/debitur" method="POST">
                    @csrf
                    <p id="nama"></p>
                    <div class="form-group">
                        <label for="nama" class=" form-control-label">Nama Perusahaan/Debitur</label>
                        <input type="text" id="nama" class="form-control" name="nm_debitur">
                    </div>
                    <div class="form-group">
                        <label for="alamat" class=" form-control-label">Alamat</label>
                        <textarea id="alamat" class="form-control" name="alamat" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="email" class=" form-control-label">Email</label>
                        <input type="email" id="email" class="form-control" name="email_deb" required>
                    </div>
                    <div class="form-group">
                        <label for="telepon" class=" form-control-label">No. Telepon</label>
                        <input type="number" id="telepon" class="form-control" name="tlp_deb" required>
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
        let url = '{{ url("") }}'

        $(document).ready(function() {
            $(document).on('click', '#edit', function() {
                var id = $(this).data('id');
                var nm_debitur = $(this).data('nm_debitur');
                var email_deb = $(this).data('email_deb');
                var alamat = $(this).data('alamat');
                var tlp_deb = $(this).data('tlp_deb');

                var state = $(this).data('state');

                if(state == "ubah"){
                    $("#modalTitle").empty();
                    $("#modalTitle").append( `Ubah Data Debitur`);
                    $("#isiModal").empty();
                    $( "#isiModal" ).append( `
                @csrf
                            <div class="form-group">
                                <input type="text" id="id" class="form-control" name="id" value="${id}" hidden>
                            </div>
                <div class="form-group">
                            <label for="nama" class=" form-control-label">Nama Perusahaan/Debitur</label>
                            <input type="text" id="nama" class="form-control" name="nm_debitur" value="${nm_debitur}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class=" form-control-label">Alamat</label>
                            <textarea id="alamat" class="form-control" name="alamat" required>${alamat}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="email" class=" form-control-label">Email</label>
                            <input type="email" id="email_deb" class="form-control" name="email_deb" value="${email_deb}" required>
                        </div>
                        <div class="form-group">
                            <label for="telepon" class=" form-control-label">No. Telepon</label>
                            <input type="number" id="tlp_deb" class="form-control" name="tlp_deb" value="${tlp_deb}" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>

                ` );
                }else{
                    $("#modalTitle").empty();
                    $("#modalTitle").append( `Detail Debitur`);
                    $("#isiModal").empty();
                    $( "#isiModal" ).append( `

                    <div class="form-group">
                            <label for="nama" class=" form-control-label">Nama Perusahaan/Debitur</label>
                            <input type="text" id="nama" class="form-control" name="nm_debitur" value="${nm_debitur}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class=" form-control-label">Alamat</label>
                            <textarea id="alamat" class="form-control" name="alamat" readonly>${alamat}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="email" class=" form-control-label">Email</label>
                            <input type="email" id="email_deb" class="form-control" name="email_deb" value="${email_deb}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="telepon" class=" form-control-label">No. Telepon</label>
                            <input type="number" id="tlp_deb" class="form-control" name="tlp_deb" value="${tlp_deb}" readonly>
                        </div>

                    ` )
                }
            })
        })

        function reset() {
            document.getElementById('debitur-name').value = '';
            $(document).ready(function() {
                let debName = document.getElementById("debitur-name").value;
                $.get(url + '/debitur-api?nm_debitur=' + debName, function(response) {
                    buildTable(response);
                });
            })
        }

        function search(){
            $(document).ready(function() {
                let debName = document.getElementById("debitur-name").value;
                $.get(url + '/debitur-api/?nm_debitur=' + debName, function(response) {
                    buildTable(response);
                });
            })
        }

        function buildTable(response) {
            var table = document.getElementById('debt-table')
            $('#debt-table tbody').empty();
            for (var i = 0; i < response.length; i++) {
                var row = `
                    <td>${i+1}</td>
                    <td>${response[i].nm_debitur}</td>
                    <td>${response[i].email_deb}</td>
                    <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="${response[i].id}" data-nm_debitur="${response[i].nm_debitur}" data-email_deb="${response[i].email_deb}" data-alamat="${response[i].alamat}" data-tlp_deb="${response[i].tlp_deb}" data-state="ubah">
                                        Ubah
                                    </button>
                                </td>
                                <td style="width: 25px;">
                                    <form action="${url + '/debitur/' + response[i].id}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus ${response[i].nm_debitur}?')">Hapus</button>
                                    </form>
                                </td>
                                <td style="width: 25px;">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="edit" data-toggle="modal" data-target="#modal-edit-debitur" data-id="${response[i].id}" data-nm_debitur="${response[i].nm_debitur}" data-email_deb="${response[i].email_deb}" data-alamat="${response[i].alamat}" data-tlp_deb="${response[i].tlp_deb}" data-state="detail">
                                        Detail
                                    </button>
                                </td>
                    `
                table.innerHTML += row

            }
        }
    </script>
    @endsection
