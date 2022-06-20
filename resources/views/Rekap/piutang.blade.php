@extends('main')

@section('title', 'Rekapitulasi Piutang')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Rekapitulasi Piutang</h1>
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
			<div class="card">
				<div class="card-body">
                    <form action="/get-rekap-piutang" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Jenis Debitur</label>
                            <div class="col-sm-8">
                              @if ($flag == "before-search")
                              <select class="js-example-basic-multiple form-control" name="debiturId[]" id="debiturId" multiple="multiple" required>
                                <option value="all">Pilih Semua</option>
                                @foreach($debitur as $deb)
                                <option value="{{ $deb->id }}">{{ $deb->nm_debitur }}</option>
                                @endforeach
                              </select>
                              @else
                              <select class="js-example-basic-multiple form-control" name="debiturId[]" id="debiturId" multiple="multiple" required>
                                <option value="all">Pilih Semua</option>
                                @foreach($debiturs as $debitur)
                                <option value="{{ $debitur->id }}">{{ $debitur->nm_debitur }}</option>
                                @endforeach
                              </select>
                              @endif
                              {{-- <input type="text" class="form-control" name="coba" style="margin-left: 15px" id="debitur">
                              <select class="form-control" name="id_debitur" id="source" onchange="getIdDebitur()" style="margin-left: 15px">
                                  @foreach ($debitur as $item)
                                  <option value="{{$item->id}}">{{$item->nm_debitur}}</option>
                                  @endforeach
                                </select> --}}

                            </div>
                          </div>
                            <div class="form-group row">
                              <label for="inputPassword3" class="col-sm-2 col-form-label">Periode Piutang s.d Bulan Ini</label>
                              <div class="col-sm-8">
                                  <div class="row">
                                      <div class="col">
                                          <h6 class="text-warning">Dari</h6>
                                          <input class="form-control" type="date" name="from" id="from"  required>
                                      </div>
                                      <div class="col">
                                          <h6 class="text-warning">Sampai</h6>
                                          <input class="form-control" type="date" name="to" id="to" required>
                                      </div>
                                    </div>
                              </div>
                            </div>

                            <button class="btn btn-danger text-light" type="submit">
                              <i class="fa fa-times"></i>
                              Batal
                          </button>
                            <button class="btn btn-warning text-light" type="submit">
                                <i class="fa fa-eye"></i>
                                Lihat
                            </button>
                            <button class="btn btn-success" type="submit">
                              <i class="fa fa-print"></i>
                              Cetak
                          </button>
                        </form>
				</div>
			  </div>
            <div class="pull-right">
            </div>
            {{-- DATA TABLE SECTION --}}
            @if ($flag == "before-search")
            <div class="card">
                <div class="card-header">
                  -
                </div>
                <div class="card-body text-center">
                  Data Empty
                </div>
              </div>
            @else
            <div class="card">
                <div class="card-header">
                  Featured
                </div>
                <div class="card-body">
                
                     <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Invoice</th>
                                <th>Nama Debitur</th>
                                <th>Piutang s.d Bulan Ini</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Total Pembayaran</th>
                                <th>Sisa Piutang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($queryResult as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_invoice }}</td>
                                <td>{{ $item->nm_debitur }}</td>
                                <td>@currency($item->total_tagihan)</td>
                                @if ($item->tgl_pembayaran == null)
                                <td class="text-center">-</td>
                                @else
                                <td class="text-center">{{ $item->tgl_pembayaran->isoFormat('D MMMM Y') }}</td>
                                @endif
                                <td>@currency($item->total_pembayaran)</td>
                                <td>@currency($item->sisa_piutang)</td>
                            </tr>
                            @endforeach
                        </tbody>
                     </table>
                
                </div>
              </div>
            @endif
           
        </div>
<style>
    table, th, td {

  border: 1px solid black;
  border-collapse: collapse;
  font-size: 1rem;
}
th{
    padding-left: 10px;
    padding-right: 10px;
}
td{
    padding-left: 10px;
    padding-right: 10px;
}

</style>
<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();

});


function previewReport(){
    const date_1 = new Date(document.getElementById("tanggal1").value).toJSON().slice(0, 19).replace('T', ' ')
    const date_2 = new Date(document.getElementById("tanggal2").value).toJSON().slice(0, 19).replace('T', ' ')
    const date_3 = new Date(document.getElementById("tanggal3").value).toJSON().slice(0, 19).replace('T', ' ')
    const date_4 = new Date(document.getElementById("tanggal4").value).toJSON().slice(0, 19).replace('T', ' ')


        let payload = (
        {
            date_1: date_1,
            date_2: date_2,
            date_3: date_3,
            date_4: date_4,
            debiturId: $(".js-example-basic-multiple").val()
        }
        );


        axios.post('/get-piutang-bulan-lalu', {
            payload
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });

}


</script>
@endsection
