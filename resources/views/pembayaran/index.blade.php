@extends('main')

@section('title', 'Pembayaran')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Pembayaran <span></span></h1>
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
                    <input type="text" class="form-control" placeholder="Cari Nomor Invoice, Nama Debitur" aria-label="Recipient's username" aria-describedby="basic-addon2" id="debitur-name" onkeyup="search()">
                    <div class="input-group-append" id="basic-addon2">
                        <button href="" class=" btn btn-info" onclick="search()">
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
                <table class="table table-bordered table-hover  table-sm" id="payment-table">
                    <thead>
                        <tr>
                            <th>No</th>
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
                        @foreach($pembayaran as $index=>$item)
                        <tr id="row-data">
                            <td>{{$index+1}}</td>
                            <td>{{ $item->piutang->no_invoice }}</td>
                            <td>{{ $item->piutang->debitur->nm_debitur }}</td>
                            <td>{{ $item->piutang->tgl_tempo->isoFormat('D MMMM Y') }}</td>
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
        </div>
    </div>
</div>
<script>
     let url = '{{ url("") }}'

     function rupiah(angka){
        var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return ribuan;
        }

        function search(){
            let debName = document.getElementById("debitur-name").value;
            axios.get(`${url}/pembayaran-api?search_payload=${debName}`)
                .then(function (response) {
                   buildTable(response.data);
                })
                .catch(function (error) {
                    alert("Gagal melakukan pencarian!");
                })
        }

        function reset(){
            document.getElementById('debitur-name').value = '';
            let debName = document.getElementById("debitur-name").value;
            axios.get(`${url}/pembayaran-api?search_payload=${debName}`)
                .then(function (response) {
                   buildTable(response.data);
                })
                .catch(function (error) {
                    alert("Gagal melakukan pencarian!");
                })
        }

        function buildTable(response){
            var table = document.getElementById('payment-table');
            $('#payment-table tbody').empty();

            for (var i = 0; i < response.length; i++) {
                if(response[i].total_pembayaran < response[i].total_tagihan){
                    status = "Belum Lunas"
                    style="background-color: #ff6e63; color:#b80c00;"
                }
                else if(response[i].total_pembayaran >= response[i].total_tagihan){
                    status = "Lunas"
                    style="background-color: #91ff9e; color:#009912;"
                }else{
                    status = "Lunas"
                    style="background-color: #91ff9e; color:#009912;"
                }

                var row = `
                    <td>${i+1}</td>
                    <td>${response[i].no_invoice}</td>
                    <td>${response[i].nm_debitur}</td>
                    <td>${moment.utc(response[i].tgl_tempo).local().format('D MMMM YYYY')}</td>
                    <td>Rp. ${rupiah(response[i].total_tagihan)}</td>
                    <td>Rp. ${rupiah(response[i].total_pembayaran)}</td>
                    <td style="${style}">${status}</td>
                    <td style="width: 25px;">
                        <a class="btn btn-info btn-sm text-light" href="/detail-pembayaran?id_piutang=${response[i].id_piutang}&total_tagihan=${response[i].total_tagihan}&id_pembayaran=${response[i].id}">Detail</a>
                    </td>
                    `
                table.innerHTML += row

            }
        }
</script>
@endsection
