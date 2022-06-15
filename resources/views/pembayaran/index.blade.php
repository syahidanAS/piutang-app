@extends('main')

@section('title', 'Pembayaran')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Data Pembayaran <span></span></h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
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
                {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-add-pembayaran">
                    <i class="fa fa-plus"></i> Buat Pembayaran
                </button> --}}
            </div>
            <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">
                <table class="table table-bordered table-hover  table-sm" id="service-table">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Nama Debitur</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Total Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaran as $item)
                        <tr id="row-data">
                            <td>{{ $item->no_invoice }}</td>
                            <td>{{ $item->nm_debitur }}</td>
                            <td>{{ $item->tgl_tempo->isoFormat('D MMMM Y') }}</td>
                            <td>@currency($item->total_tagihan)</td>
                            <td>@currency($item->total_pembayaran)</td>
                            @if($item->total_pembayaran < $item->total_tagihan )
                            <td style="background-color: #ff6e63; color:#b80c00;">Belum Lunas</td>
                            @elseif($item->total_tagihan == $item->total_pembayaran)
                            <td style="background-color: #91ff9e; color:#009912;">Lunas</td>
                            @else
                            <td style="background-color: #91ff9e; color:#009912;">Lunas</td>
                            @endif

                            <td class="text-center">
                                <a class="btn btn-info btn-sm btn-block" href="/detail-pembayaran?id_piutang={{ $item->id_piutang }}&total_tagihan={{$item->total_tagihan}}&id_pembayaran={{$item->id}}">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PEMBAYARAN -->
<div class="modal fade" id="modal-add-pembayaran" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Data Jenis Pengobatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/create-payment" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="id_piutang">Debitur</label>
                        <select class="form-control" id="id_piutang" name="id_piutang" onchange="getTotalTagihan()" required>
                        @foreach($getInvoice as $item)
                          <option value="{{ $item->id }}" >{{ $item->no_invoice . " - " . $item->nm_debitur }}</option>
                        @endforeach
                        </select>
                      </div>
                    <div class="form-group">
                        <label for="natotal_tagihanma" class=" form-control-label">Total Tagihan</label>
                        <input type="number" id="total_tagihan" class="form-control" name="total_tagihan" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

          var idPiutang = document.getElementById("id_piutang");
        var strId = idPiutang.value; // 2
        var strId = idPiutang.options[idPiutang.selectedIndex].value; //test2


        axios.get(`/get-total-tagihan?id_piutang=${strId}`)
            .then(function (response) {
                if(response.data[0].total_tagihan == null){
                    document.getElementById("total_tagihan").value = 0;
                    document.getElementById("btnSimpan").disabled = true;
                }else{
                    document.getElementById("total_tagihan").value = response.data[0].total_tagihan;
                    document.getElementById("btnSimpan").disabled = false;
                }
            })
            .catch(function (error) {
                alert("Data tidak ditemukan!");
            })


    function getTotalTagihan(){
        var idPiutang = document.getElementById("id_piutang");
        var strId = idPiutang.value; // 2
        var strId = idPiutang.options[idPiutang.selectedIndex].value;


        axios.get(`/get-total-tagihan?id_piutang=${strId}`)
            .then(function (response) {
                if(response.data[0].total_tagihan == null){
                    document.getElementById("total_tagihan").value = 0;
                    document.getElementById("btnSimpan").disabled = true;
                }else{
                    document.getElementById("total_tagihan").value = response.data[0].total_tagihan;
                    document.getElementById("btnSimpan").disabled = false;
                }
            })
            .catch(function (error) {
                alert("Data tidak ditemukan!");
            })



    }
    function lihat(id_piutang){
    axios.get(`status-checker?id_piutang=${id_piutang}`)
        .then(function (response) {
            if(response.data.data == "Belum Lunas"){
                alertify.set('notifier','position', 'top-right');
                alertify.error(`Status Pembayaran: ${response.data.data}`);
            }else if(response.data.data == "Lunas"){
                alertify.set('notifier','position', 'top-right');
                alertify.success(`Status Pembayaran: ${response.data.data}`);
            }else if(response.data.data == "On Process"){
                alertify.set('notifier','position', 'top-right');
                alertify.error(`Status Pembayaran: Belum Lunas`);
            }
        })
        .catch(function (error) {
            console.log(error);
        })

    }
</script>
@endsection
