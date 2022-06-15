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
                    <form action="/get-piutang-bulan-lalu" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Jenis Debitur</label>
                            <div class="col-sm-8">
                              <select class="js-example-basic-multiple form-control" name="debiturId[]" id="debiturId" multiple="multiple" required>
                                  <option value="all">Pilih Semua</option>
                                  @foreach($debitur as $item)
                                  <option value="{{ $item->id }}">{{ $item->nm_debitur }}</option>
                                  @endforeach
                                </select>
                              {{-- <input type="text" class="form-control" name="coba" style="margin-left: 15px" id="debitur">
                              <select class="form-control" name="id_debitur" id="source" onchange="getIdDebitur()" style="margin-left: 15px">
                                  @foreach ($debitur as $item)
                                  <option value="{{$item->id}}">{{$item->nm_debitur}}</option>
                                  @endforeach
                                </select> --}}

                            </div>
                          </div>
                            <div class="form-group row">
                              <label for="inputPassword3" class="col-sm-2 col-form-label">Periode Piutang s.d Bulan Lalu</label>
                              <div class="col-sm-8">
                                  <div class="row">
                                      <div class="col">
                                          <h6 class="text-info">Dari</h6>
                                          <input class="form-control" type="date" id="tanggal1" name="date_1" value="{{date("Y")}}-01-01" readonly>
                                      </div>
                                      <div class="col">
                                          <h6 class="text-info">Sampai</h6>
                                          <input class="form-control" type="date" id="tanggal2" name="date_2" required>
                                      </div>
                                    </div>
                              </div>

                            </div>
                            <div class="form-group row">
                              <label for="inputPassword3" class="col-sm-2 col-form-label">Periode Piutang s.d Bulan Ini</label>
                              <div class="col-sm-8">
                                  <div class="row">
                                      <div class="col">
                                          <h6 class="text-warning">Dari</h6>
                                          <input class="form-control" type="date" name="date_3" id="tanggal3"  required>
                                      </div>
                                      <div class="col">
                                          <h6 class="text-warning">Sampai</h6>
                                          <input class="form-control" type="date" name="date_4" id="tanggal4" required>
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
            @if ($flag == "after-search")
            <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">

                <div class="col-xs-6">
                      <div class="table-responsive">
                        <table style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Invoice</th>
                                    <th>Nama Debitur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($resultBulanLalu->isEmpty())
                                @foreach ($resultBulanIni as $item)
                                <tr>
                                    <td>1</td>
                                    <td>{{$item->no_invoice}}</td>
                                    <td>{{$item->nm_debitur}}</td>
                                </tr>
                                @endforeach
                                @else
                                @foreach ($resultBulanLalu as $item)
                                <tr>
                                    <td>1</td>
                                    <td>{{$item->no_invoice}}</td>
                                    <td>{{$item->nm_debitur}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                              </div>
                    </div>
                      <div class="col-xs-6">
                        <div class="table-responsive">

                <table style="width:100%">
                    <thead>
                        <tr>
                            <th>PIUTANG S.D BULAN LALU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($resultBulanIni->isEmpty())
                        @foreach ($resultBulanLalu as $item)
                        <tr>
                            <td>@currency(0)</td>
                        </tr>
                        @endforeach
                        @else
                        @foreach ($resultBulanIni as $item)
                        <tr>
                            <td>@currency($item->piutang_bulan_Ini)</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>

                </div>
            </div>
            <div class="col-xs-6">
               <div class="table-responsive">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th>PIUTANG S.D BULAN INI</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if($resultBulanLalu->isEmpty())
                        @foreach ($resultBulanIni as $item)
                        <tr>
                            <td>@currency(0)</td>
                        </tr>
                        @endforeach
                        @else
                        @foreach ($resultBulanLalu as $item)
                        <tr>
                            <td>@currency($item->piutang_bulan_Lalu)</td>
                        </tr>
                        @endforeach
                        @endif


                    </tbody>
                </table>
               </div>
            </div>
            </div>
            @else
            <div class="card-body table-responsive" style="overflow-y: scroll; height:400px;">
                <div class="card" style="min-height: 100%;">
                    <div class="row">
                        <div style="width: 100%;text-align: center; padding-top:10%;">
                            <h5>Data Empty</h5>
                        </div>
                      </div>
                </div>
            </div>
            @endif

        </div>
    </div>
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
