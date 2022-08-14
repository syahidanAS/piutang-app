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
                    <form action="/get-rekapPiutang" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Jenis Debitur</label>
                            <div class="col-sm-8">
                              @if ($flag == "before-search")
                              <select class="js-example-basic-multiple form-control" name="debiturId[]" id="debiturId" multiple="multiple">
                                <option value="all" selected>Pilih Semua</option>
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
                            <div class="row">
                                <div class="col-1">
                                    <input class="btn btn-warning text-light" type="submit" name="submitbtn" value="preview"/>
                                </div>
                                <div class="col">
                                    <input class="btn btn-success text-light" type="submit" name="submitbtn" value="cetak"/>
                                </div>
                              </div>
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
                    @php
                        function tgl_indo($tanggal){
                            $bulan = array (
                                1 =>   'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember'
                            );
                            $pecahkan = explode('-', $tanggal);
                            return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
                        }
                    @endphp
                  Rekapitulasi Piutang Periode {{tgl_indo($from)}} sampai {{tgl_indo($to)}}
                </div>
                <div class="card-body">
                <div class="table-responsive">
                <table >
                    <?php $current_piutang = 0 ?>
                    <?php $cumulative_payment = 0 ?>
                    <?php $piutang_balance = 0 ?>
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
                            @php
                                // membuat array baru untuk query result
                                $newQueryResult = array();
                                foreach ($queryResult as $value) {
                                    $newQueryResult[$value['no_invoice']][] = $value;
                                }
                            @endphp
                            @foreach ($newQueryResult as $data)
                                <tr>
                                    <td rowspan="{{ count($data) }}" style="text-align: center;vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td rowspan="{{ count($data) }}" style="text-align: center;vertical-align: middle;">{{ $data[0]['no_invoice'] }}</td>
                                    <td rowspan="{{ count($data) }}" style="text-align: center;vertical-align: middle;">{{ $data[0]['nm_debitur'] }}</td>
                                    <td rowspan="{{ count($data) }}" style="text-align: center;vertical-align: middle;">@currency($data[0]['total_tagihan'])</td>
                                    <?php $current_piutang += $data[0]['total_tagihan']  ?>
                                    @php
                                        // variable baru untuk menampung nominal pembayaran
                                        $nominal_pembayaran = 0;
                                        //variable untuk akumulasi sisa piutang
                                        $sisa_uang = $data[0]['total_tagihan'];
                                    @endphp
                                    @foreach ($data as $key=>$item)
                                        <td>{{ $item->tgl_pembayaran->isoFormat('D MMMM Y') }}</td>
                                        @php
                                            //akumulasi nominal pembayaran
                                            $nominal_pembayaran += (int)$item->total_pembayaran;
                                            $sisa_uang -= (int)$item->total_pembayaran;
                                        @endphp
                                        <td class="text-center">@currency($item->total_pembayaran)</td>
                                        <?php $cumulative_payment +=  $item->total_pembayaran?>
                                        <td>@currency($item->sisa_piutang)</td>
                                        <?php $piutang_balance += $item->sisa_piutang ?>
                                        </tr><tr>
                                    @endforeach
                                </tr>
                            @endforeach
                            {{-- @foreach ($queryResult as $key=>$item)
                            <tr>

                            <?php $sum_current_piutang = 0 ?>

                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->no_invoice}}</td>
                                <td>{{ $item->nm_debitur }}</td>
                                <td>@currency($item->total_tagihan)</td>
                                <td>{{ $item->tgl_pembayaran->isoFormat('D MMMM Y') }}</td>
                                <td>@currency($item->total_pembayaran)</td>
                                <td>@currency($item->sisa_piutang)</td>
                            </tr>
                            <?php $sum_current_piutang += $item->total_tagihan  ?>
                            @endforeach --}}

                            <tr>
                                <td class="text-center" colspan="3">TOTAL</td>
                                <td class="text-center">@currency($current_piutang )</td>
                                <td></td>
                                <td class="text-center">@currency($cumulative_payment )</td>
                                <td class="text-center">@currency($piutang_balance )</td>
                            </tr>
                        </tbody>
                     </table>
                </div>
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
</script>
@endsection
