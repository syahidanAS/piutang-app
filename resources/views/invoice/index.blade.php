@extends('main')

@section('title', 'Invoice')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <div class="mt-2">
                    <table>
                        <tr>
                            <td><a class="btn btn-info btn-sm" href="/piutang" style="border-radius:20px;"><i class="fa fa  fa-arrow-circle-o-left" style="margin-right: 7px"></i>Kembali</a></td>
                            <td><h1 style="margin-left: 15px">Kelola Invoice</h1></td>
                        </tr>
                    </table>
                  </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="container">
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
    <div class="row">
      <div class="col-sm">
        @foreach($piutang as $item)
        <div class="card" style="border-radius: 10px;">
            <div class="card-body" style="padding-top: 20px">
                <table class="table table-responsive">
                    <tr>
                        <td><h6>Nomor Invoice</h6></td>
                        <td><h6>: </h6></td>
                        <td><h6>{{ $item->no_invoice }}</h6></td>
                    </tr>
                    <tr>
                        <td><h6>Nama Debitur</h6></td>
                        <td><h6>: </h6></td>
                        <td><h6>{{ $item->nm_debitur }}</h6></td>
                    </tr>
                    <tr>
                        <td><h6>Tanggal Pengajuan</h6></td>
                        <td><h6>: </h6></td>
                        <td><h6>{{ $item->tgl_pengajuan->isoFormat('D MMMM Y') }}</h6></td>
                    </tr>
                    <tr>
                        <td><h6>Tanggal Jatuh Tempo</h6></td>
                        <td><h6>: </h6></td>
                        <td><h6>{{ $item->tgl_tempo->isoFormat('D MMMM Y') }}</h6></td>
                    </tr>
                    <tr>
                        <td><h6>Kondisi Invoice</h6></td>
                        <td><h6>: </h6></td>

                        @if( $piutangCondition == 1)
                        <td><h6 class="p-1 rounded" style="background-color: #8cffa3; color:#00941e;">Sudah memiliki history pembayaran</h6></td>
                        @else
                        <td><h6 class="p-1 rounded" style="background-color: #ff9ca4; color:#870000;">Belum ada histori pembayaran</h6></td>
                        @endif
                    </tr>
                </table>
            </div>
          </div>
        @endforeach
      </div>
      <div class="col-sm">
        <div class="card" style="border-radius: 10px;">
            <div class="card-body">

                <div class="form-group">
                    @foreach($piutang as $item)
                    <input type="number" id="id_piutang" class="form-control" name="id_piutang" value="{{ $item->id }}" hidden>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="id_layanan">Nama Layanan</label>
                    <select class="form-control" id="id_layanan" name="id_layanan" required>
                    @foreach($pelayanan as $item)
                      <option value="{{ $item->id }}">{{ $item->nama_pelayanan }}</option>
                    @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="qty" class=" form-control-label">Qty</label>
                    <input type="number" id="qty" class="form-control" name="qty" required>
                </div>
                @if($piutangCondition == 0)
                <button type="submit" class="btn btn-primary btn-block btn-sm" onclick="postData()">Buat Invoice</button>
                @else
                <button type="submit" class="btn btn-secondary btn-block btn-sm" onclick="denied('Maaf tidak dapat menambahkan layanan karena invoice sudah memiliki histori pembayaran')">Buat Invoice</button>
                @endif
            </div>
          </div>
      </div>
    </div>
  </div>



<div class="container" style="">
    @foreach($piutang as $item)
    {{-- <a class="btn btn-success btn-sm mb-2" href="/cetak-invoice?id={{ $item->id }}" style="border-radius:5px;" target="_blank"><i class="fa fa-print" style="margin-right: 7px"></i>Cetak Invoice</a> --}}
    <button id="btnPrint" type="button" class="btn btn-info btn-sm mb-2" data-toggle="modal" data-target="#printmodal" style="border-radius:5px;" data-id="{{ $item->id }}">
        <i class="fa fa-print" style="margin-right: 7px"></i>Cetak
    </button>
    <div class="modal fade" id="printmodal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Print Properties</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/cetak-invoice" method="POST" target="_blank">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="text" id="idCetak" class="form-control" name="id" hidden>
                                </div>
                                <div class="form-group">
                                    <label for="potongan" class=" form-control-label">Potongan</label>
                                    <input type="number" id="potongan" class="form-control" name="potongan" value="0" required>
                                </div>
                                <div class="form-group">
                                    <label for="materai" class=" form-control-label">Biaya Materai</label>
                                    <input type="number" id="materai" class="form-control" name="materai" value="0" required>
                                </div>
                                <div class="form-group">
                                    <a class="btn btn-outline-info btn-sm text-info" onclick="hitungGrandTotal()">Hitung</a>
                                </div>
                                <div class="form-group">
                                    <h6>Grand Total: <span id="grandTotal"></span></h6>
                                </div>
                            </div>
                            <div class="col pt-3">
                                <div class="form-group">
                                    <label for="terbilang" class=" form-control-label">Terbilang</label>
                                    <input type="text" id="terbilang" class="form-control" name="terbilang" placeholder="Misal: Satu Juta Rupiah" required>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan_invoice" class=" form-control-label">Keterangan</label>
                                    <textarea id="keterangan_invoice" class="form-control" name="keterangan_invoice" required></textarea>
                                </div>
                            </div>
                          </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary"> Cetak</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @if($piutangCondition == 0)
    @foreach($piutang as $item)
    <form action="/create-payment" method="POST">
        @csrf
        <input type="number" name="total_tagihan" id="tagihan_posting" value="{{$total->sub_total}}" hidden>
        <input type="text" name="id_piutang" value="{{ $idPiutang }}" hidden>
        <input type="text" name="tgl_pengajuan" value="{{ $item->tgl_pengajuan }}" hidden>
        <button class="btn btn-success btn-sm mb-2" type="submit" onclick="return confirm('Jika pembayaran sudah diposting, invoice tidak dapat diubah!')"><i class="fa fa-upload" style="margin-right: 7px"></i>Posting Pembayaran</button>
    </form>
    @endforeach
   @else
   <br>
   <button class="btn btn-success btn-sm mb-2" style="border-radius:5px;" onclick="hasPosting()"><i class="fa fa-upload" style="margin-right: 7px"></i>Posting Pembayaran</button>
    @endif
   @endforeach
    <div class="card-body table-responsive" style="background-color: #fefefe; overflow-y: scroll; max-height:200px;">
        <table class="table table-bordered table-hover table-sm" id="inv-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelayanan</th>
                    <th>Qty</th>
                    <th>Harga Per Unit</th>
                    <th>Total Harga</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoice as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_pelayanan }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>@currency($item->harga)</td>
                    <td>@currency($item->total)</td>
                    <td class="text-center">
                        <form action="{{ url('/invoice-delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="id_piutang" value="{{ $item->id_piutang }}">
                            <input type="hidden" name="tagihanFix" value="{{ $item->total }}">
                            @if($piutangCondition == 0)
                            <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                            @else
                            <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" disabled>Hapus</button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <td class="text-center" colspan="6"><h6>Invoice ini belum memiliki layanan</h6></td>
                @endforelse
            </tbody>
        </table>
    </div>
   <div class="card mt-2 bg-secondary text-light" style="margin-left: 63%; border-radius: 10px; padding-left:20px; padding-right:20px;">
       <div class="card-body text-center">
        <h6 >Total Tagihan Sementara: <span id="total-value">@currency($total->sub_total)</span></h6>
       </div>
   </div>
   <input type="number" id="subTotalTemporary" value="{{ $total->sub_total }}" hidden>
</div>
<script>
function hasPosting(){
    alertify.set('notifier','position', 'top-right');
    alertify.error(`Pembayaran pada invoice ini sudah diposting`);
}
function denied(message){
    alertify.set('notifier','position', 'top-right');
    alertify.error(`${message}`);
}
function rupiah(angka){
   var reverse = angka.toString().split('').reverse().join(''),
   ribuan = reverse.match(/\d{1,3}/g);
   ribuan = ribuan.join('.').split('').reverse().join('');
   return ribuan;
 }

    function hitungGrandTotal(){
        let total = document.getElementById("subTotalTemporary").value;
        let potongan = document.getElementById("potongan").value;
        let materai = document.getElementById("materai").value;
        let final = parseInt(total)+parseInt(materai)-parseInt(potongan);
        document.getElementById("grandTotal").innerHTML=new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR"}).format(final);
    }

    $(document).ready(function() {
            $(document).on('click', '#btnPrint', function() {
                var id = $(this).data('id');
                $('#idCetak').val(id);
            })
        })


    let url = '{{ url("") }}'
    function reload(){
        $(document).ready(function() {
            let id_piutang = document.getElementById("id_piutang").value;
                $.get(url + '/invoice-api/?id_piutang=' + id_piutang, function(response) {
                    buildTable(response.invoice, response[0]);
                });
            })
    }




    function buildTable(response, responseTotal) {
            var table = document.getElementById('inv-table')
            $('#inv-table tbody').empty();
            for (var i = 0; i < response.length; i++) {
                var row = `
                    <td>${i+1}</td>
                    <td>${response[i].nama_pelayanan}</td>
                    <td>${response[i].qty}</td>
                    <td>Rp. ${rupiah(response[i].harga)}</td>
                    <td>Rp. ${rupiah(response[i].total)}</td>
                    <td class="text-center">
                        <form action="{{ url('/invoice-delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="${response[i].id}">
                            <input type="hidden" name="id_piutang" value="${response[i].id_piutang}">
                            <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                    `
                table.innerHTML += row
                document.getElementById("total-value").innerHTML=new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR"}).format(responseTotal);
                document.getElementById("tagihan_posting").value=responseTotal;
                document.getElementById("subTotalTemporary").value=responseTotal;
            }
        }


    function postData(){
        let id_piutang = document.getElementById("id_piutang").value;
        let id_layanan = document.getElementById("id_layanan").value;
        let qty = document.getElementById("qty").value;

        let payload = {
            "id_piutang":id_piutang,
            "id_layanan":id_layanan,
            "qty":qty
        }

        axios.post('/invoice',payload)
        .then(function () {
            Swal.fire(
                'Berhasil!',
                'Berhasil menambahkan item',
                'success'
                );
                document.getElementById('qty').value = '';
                $('#id_layanan').prop('selectedIndex',0);
                reload();
        })
        .catch(function (error) {
            if(error.response.status === 400){
                Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menambahkan item, mohon coba lagi',
                })
            }else if(error.response.status === 409){
                Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Layanan sudah ditambahkan, mohon periksa kembali!',
                })
            }

        });

            }
</script>
@endsection


