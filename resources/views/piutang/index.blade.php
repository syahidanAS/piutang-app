@extends('main')

@section('title', 'Piutang')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Data Piutang <span></span></h1>
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
                        <input type="text" class="form-control" placeholder="Cari Piutang" aria-label="Recipient's username" aria-describedby="basic-addon2" id="invoice-number" onkeyup="search()">
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
                    <table class="table table-bordered table-hover table-sm" id="debt-table">
                        <thead>
                            <tr>
                                <th>No</th>

                                <th>Nomor Invoice</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Jatuh Tempo</th>
                                <th>Nama Debitur</th>
                                <th>Total Piutang</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($piutang as $item)
                            <tr id="row-data">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_invoice }}</td>
                                <td>{{ $item->tgl_pengajuan->isoFormat('D MMMM Y') }}</td>
                                <td>{{ $item->tgl_tempo->isoFormat('D MMMM Y') }}</td>
                                <td>{{ $item->nm_debitur }}</td>
                                <td>@currency($item->total_piutang)</td>
                                {{-- <td>{{ "Rp " . number_format($item->tagihan,2,',','.'); }}</td> --}}

                                @if($item->status_piutang == "Lunas" )
                                <td class="text-light" style="background-color:#03fc28;">Lunas</td>
                                @elseif($item->due <= 0)
                                <td class="text-success" style="background-color:#e1f0e4;">Lancar</td>
                                @elseif($item->due >= 0 && $item->due <= 30)
                                <td class="text-success" style="background-color:#e1f0e4;">Lancar</td>
                                @elseif($item->due >= 30 && $item->due <= 60)
                                <td class="text-info" style="background-color:#b3bff5;">Kurang Lancar</td>
                                @elseif($item->due >= 60 && $item->due <= 90)
                                <td class="text-warning" style="background-color:#f7f1d5;">Diragukan</td>
                                @elseif($item->due >= 95)
                                <td class="text-danger" style="background-color:#f5b3b5;">Macet</td>
                                @endif
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <div class="dropdown-item">
                                                <a href="/invoice?id={{ $item->id }}"><i class="fa  fa-file-text-o"></i> Kelola Tagihan</a>
                                              </div>
                                          <div class="dropdown-item">
                                            <i class="fa fa-pencil"></i> <a href="" type="button" class="text-dark" id="edit" data-toggle="modal" data-target="#modal-edit-piutang"
                                            data-id="{{ $item->id }}"
                                            data-no-invoice="{{ $item->no_invoice }}"
                                            data-tgl-pengajuan="{{ $item->tgl_pengajuan }}"
                                            data-tgl-tempo="{{ $item->tgl_tempo }}"

                                            data-nama-debitur="{{ $item->nm_debitur }}"
                                            data-id-debitur="{{ $item->id_debitur }}">
                                            Edit
                                         </a>
                                          </div>

                                          <div class="dropdown-item">
                                            <form action="{{ url('/piutang/'.$item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <i class="fa fa-trash"></i>  <button class="btn btn-outline-white text-dark" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>

                                            </form>
                                          </div>

                                          <div class="dropdown-item">
                                            <a href="/cetak-piutang?id={{ $item->id }}" target="_blank"><i class="fa fa-print"></i> Cetak Surat Tagihan</a>
                                          </div>
                                        </div>
                                      </div>
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
    <div class="modal fade" id="modal-edit-piutang" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">UBAH DATA PIUTANG</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/edit-piutang" method="POST">
                        @csrf
                          <div class="row">
                            <div class="col">
                                <strong>INFORMASI PIUTANG</strong>
                                <hr>
                                <input type="text" id="id" class="form-control" name="id" hidden>
                                <div class="form-group">
                                    <label for="no_invoice" class=" form-control-label">Nomor Invoice/Surat Penagihan</label>
                                    <input type="text" id="no_invoice" class="form-control" name="no_invoice" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="tgl_pengajuan" class=" form-control-label">Tanggal Pengajuan</label>
                                    <input type="date" id="pengajuan1" class="form-control" name="tgl_pengajuan" onchange="dueGeneratorEdit()" required>
                                </div>

                                <div class="form-group" >
                                    <label for="tgl_tempo" class=" form-control-label">Tanggal Jatuh Tempo</label>
                                    <input type="date" id="tempo1" class="form-control" name="tgl_tempo" required>
                                </div>
                            </div>
                            <div class="col">
                                <strong>INFORMASI DEBITUR/LAYANAN</strong>
                            <hr>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Debitur</label>
                                <select class="form-control" id="id_debitur" name="id_debitur" required>
                                @foreach($debitur as $item)
                                  <option value="{{ $item->id }}">{{ $item->nm_debitur }}</option>
                                @endforeach
                                </select>
                              </div>
                              <hr>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH DEBITUR -->
<div class="modal fade" id="modal-add-debitur" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">FORMULIR PENGAJUAN PIUTANG</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/piutang" method="POST">
                    @csrf
                      <div class="row">
                        <div class="col">
                            <strong>INFORMASI PIUTANG</strong>
                            <hr>
                            {{-- <div class="form-group">
                                <label for="no_invoice" class=" form-control-label">Nomor Invoice</label>
                                <input type="text" id="no_invoice" class="form-control" name="no_invoice" required>
                            </div> --}}
                            <div class="form-group">
                                <label for="tgl_pengajuan" class=" form-control-label">Tanggal Pengajuan</label>
                                <input type="date" id="tgl_pengajuan_add" class="form-control" name="tgl_pengajuan" onchange="dueGeneratorAdd()" required>
                            </div>
                            <div class="form-group" >
                                <label for="tgl_tempo" class=" form-control-label">Tanggal Jatuh Tempo</label>
                                <input type="date" id="tgl_tempo_add" class="form-control" name="tgl_tempo" required>
                            </div>
                        </div>
                        <div class="col">
                            <strong>INFORMASI DEBITUR/LAYANAN</strong>
                        <hr>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Debitur</label>
                            <select class="form-control" id="id_debitur" name="id_debitur" required>
                            @foreach($debitur as $item)
                              <option value="{{ $item->id }}">{{ $item->nm_debitur }}</option>
                            @endforeach
                            </select>
                          </div>
                          <hr>
                          <i class="fa fa-info-circle text-primary"></i> Nomor invoice/surat tagihan akan di-generate secara otomatis
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>

        function dueGeneratorAdd() {
			// document.getElementById("jatuh-tempo").reset();
			const pengajuan = document.getElementById("tgl_pengajuan_add").value;

			let currentDate = new Date(pengajuan);
			let dueDate = new Date(new Date().setDate(currentDate.getDate() + 30));

			document.getElementById("tgl_tempo_add").value = dueDate.toJSON().slice(0, 10);
            }

         function dueGeneratorEdit() {
			// document.getElementById("jatuh-tempo").reset();
			const pengajuan = document.getElementById("pengajuan1").value;

			let currentDate = new Date(pengajuan);
			let dueDate = new Date(new Date().setDate(currentDate.getDate() + 30));

			document.getElementById("tempo1").value = dueDate.toJSON().slice(0, 10);
            }


        var url = '{{ url("") }}'
        $(document).ready(function() {
            $(document).on('click', '#edit', function() {
                var id = $(this).data('id');
                var no_invoice = $(this).data('no-invoice');
                var tgl_pengajuan = $(this).data('tgl-pengajuan');
                var tgl_tempo = $(this).data('tgl-tempo');
                var nama_debitur = $(this).data('nama-debitur');
                var id_debitur = $(this).data('id-debitur');

                var pengajuan = new Date(tgl_pengajuan);
                var day = ("0" + pengajuan.getDate()).slice(-2);
                var month = ("0" + (pengajuan.getMonth() + 1)).slice(-2);
                var tanggal_pengajuan = pengajuan.getFullYear()+"-"+(month)+"-"+(day) ;

                var tempo = new Date(tgl_tempo);
                var day = ("0" + tempo.getDate()).slice(-2);
                var month = ("0" + (tempo.getMonth() + 1)).slice(-2);
                var tanggal_jatuh_tempo = tempo.getFullYear()+"-"+(month)+"-"+(day) ;


                $('#id').val(id);
                $('#no_invoice').val(no_invoice);
                $('#pengajuan1').val(tanggal_pengajuan);
                $('#tempo1').val(tanggal_jatuh_tempo);


                let select_debitur = document.querySelector('#id_debitur');
                select_debitur.value = id_debitur;
            })
        })

        function reset() {
            document.getElementById('invoice-number').value = '';
            $(document).ready(function() {
                let debName = document.getElementById("invoice-number").value;
                $.get(url + '/piutang-api/?no_invoice=' + debName, function(response) {
                    buildTable(response);
                });
            })
        }

        function search(){
            $(document).ready(function() {
                let debName = document.getElementById("invoice-number").value;
                $.get(url + '/piutang-api/?no_invoice=' + debName, function(response) {
                    buildTable(response);
                });
            })
        }
        const rupiah = (number)=>{
            return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
            }).format(number);
        }
        function buildTable(response) {
            var table = document.getElementById('debt-table')
            $('#debt-table tbody').empty();
            for (var i = 0; i < response.length; i++) {
                let due, textColor, style;
                if(response[i].status_piutang == "Lunas"){
                    due = "Lunas"
                    textColor = "text-light"
                    style = "background-color:#03fc28;"
                }
                else if(response[i].due <= 0){
                    due = "Lancar"
                    textColor = "text-success"
                    style = "background-color:#e1f0e4;"
                }else if(response[i].due >= 0 && response[i].due <= 30){
                    due = "Lancar"
                    textColor = "text-success"
                    style = "background-color:#e1f0e4;"
                }else if(response[i].due >= 30 && response[i].due <= 60){
                    due = "Kurang Lancar"
                    textColor = "text-info"
                    style = "background-color:#b3bff5;"
                }else if(response[i].due >= 6 && response[i].due <= 90){
                    due = "Diragukan"
                    textColor = "text-warning"
                    style = "background-color:#f7f1d5;"
                }else if(response[i].due >= 95){
                    due = "Macet"
                    textColor = "text-danger"
                    style = "background-color:#f5b3b5;"
                }
                var row = `
                    <td>${i+1}</td>
                    <td>${response[i].no_invoice}</td>
                    <td>${moment.utc(response[i].tgl_pengajuan).local().format('D MMMM YYYY')}</td>
                    <td>${moment.utc(response[i].tgl_tempo).local().format('D MMMM YYYY')}</td>
                    <td>${response[i].nm_debitur}</td>
                    <td>${response[i].total_piutang}</td>
                    <td class="${textColor}" style="${style}">${due}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="dropdown-item">
                                    <i class="fa fa-file-text-o"></i> <a class="text-dark" href="/invoice?id=${response[i].id}">Kelola Tagihan</a>
                                </div>
                                <div class="dropdown-item">
                                    <i class="fa fa-pencil"></i> <a href="" type="button" class="text-dark" id="edit" data-toggle="modal" data-target="#modal-edit-piutang"
                                            data-id="${response[i].id}"
                                            data-no-invoice="${response[i].no_invoice}"
                                            data-tgl-pengajuan="${response[i].tgl_pengajuan}"
                                            data-tgl-tempo="${response[i].tgl_tempo}"
                                            data-nama-debitur="${response[i].nm_debitur}"
                                            data-id-debitur="${response[i].id_debitur}">

                                        Edit/Detail Piutang
                                    </a>
                                </div>

                                <div class="dropdown-item">
                                    <form action="${url + '/piutang/' + response[i].id}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <i class="fa fa-trash"></i><button class="btn btn-outline-secondary btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>

                                    </form>
                                </div>
                                <div class="dropdown-item">
                                            <a href="/cetak-piutang?id=${response[i].id}" target="_blank"><i class="fa fa-print"></i> Cetak Surat Tagihan</a>
                                </div>

                            </div>
                        </div>
                    </td>
                    `
                table.innerHTML += row

            }
        }
    </script>
    @endsection
