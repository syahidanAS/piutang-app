@extends('main')

@section('title', 'Jurnal')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Jurnal Umum</h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="card mx-2">
	<div class="card-body">
	  <p class="card-title">Filter Periode Jurnal Umum</p>

      <div style="float:left;">
        <form action="/jurnal-after-search" method="POST">
            @csrf
            <div class="row">
                <div class="col">
                    <input class="form-control" type="date" id="from" onchange="getFrom()" name="from" required>
                </div>
                -
                <div class="col">
                    <input class="form-control" type="date" id="to" onchange="getTo()"  name="to"required>
                </div>
                <div class="col">
                    <button class="btn btn-info " type="submit"><i class="fa fa-filter" onclick="search()"></i> Filter</button>
                </div>
              </div>


            {{-- <?php $years = range(2017, strftime("%Y", time())); ?>
            <select name="tahun" onchange="getValue()" id="year-periode" style="font-size: 18px; ">
				<option value="choose">Pilih Tahun</option>
                <?php foreach($years as $year) : ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
              <?php endforeach; ?>
            </select> --}}
        </form>
      </div>

      <div style="float:left; margin-left:5px;">
        {{-- <form action="/cetak-jurnal" method="POST" target="_blank"> --}}
        <form onclick="alert('Sebentar ya masih dimasak :)')">
            @csrf
            <input class="" type="text" id="from-text" name="tahun" hidden  required>
            <input class="" type="text" id="to-text" name="tahun" hidden  required>
            <button class="btn btn-success" type="submit"><i class="fa fa-print" onclick="search()"></i> Cetak</button>
        </form>
      </div>


	</div>
  </div>
  @if(count($dataJurnal) <= 0)
  <div class="card mx-2">
	<div class="card-header">
		{{-- <h4 id="periodeValue">Rekap Umur Piutang Tahun {{$tahun}}</h4> --}}
	</div>
	<div class="card-body text-center">
       {{-- <p>Data jurnal pada tahun {{$tahun}} tidak ditemukan</p> --}}
	</div>
  </div>


  @else
  <div class="card mx-2">
	<div class="card-header">
		<h4 id="periodeValue"></h4>
	</div>
	<div class="card-body">
    <table class="w-100">
      <thead>
          <tr class="text-center">
              <th>No. Jurnal</th>
              <th>Tanggal Jurnal</th>
              <th>Keterangan</th>
              <th>Kode Perkiraan</th>
              <th>Nama Perkiraan</th>
              <th>Debet</th>
              <th>Kredit</th>
          </tr>
      </thead>
      <tbody>
        <?php $debet_1=0 ?>
        <?php $debet_2=0 ?>
        <?php $kredit_1=0 ?>
        <?php $kredit_2=0 ?>
          @foreach ($dataJurnal as $key=>$item)
          <tr>
              @if ($key == 0 || $key % 2 == 0)
              <td class="text-center" rowspan="2">{{ $item->no_jurnal }}</td>
              <td class="text-center" rowspan="2">{{ $item->tanggal->isoFormat('D MMMM Y') }}</td>
              <td rowspan="2">{{ $item->keterangan }}</td>
              @endif
              <td class="text-center" style="border-bottom-style:none;">{{ $item->kode_perkiraan }}</td>
              <td class="text-center">{{ $item->nama_perkiraan }}</td>
              @if($item->flag == "piutang-pendapatan")
              <td class="text-center" style="border-bottom-style:none;">@currency($item->nominal)</td>
              <td class="text-center">-</td>
              <?php $debet_1 += $item->nominal  ?>
              @elseif($item->flag == "pendapatan-piutang")
              <td class="text-center" style="border-bottom-style:none;">-</td>
              <td class="text-center">@currency($item->nominal)</td>
              <?php $kredit_1 += $item->nominal  ?>
              @elseif($item->flag == "kas-piutang")
              <td class="text-center" style="border-bottom-style:none;">@currency($item->nominal)</td>
              <td class="text-center">-</td>
              <?php $debet_2 += $item->nominal  ?>
              @elseif($item->flag == "piutang-kas")
              <td class="text-center" style="border-bottom-style:none;">-</td>
              <td class="text-center">@currency($item->nominal)</td>
              <?php $kredit_2 += $item->nominal  ?>
              @endif
          </tr>

          @endforeach
          <tr>
            <td class="text-center" colspan="5"><b>TOTAL</b></td>
            <td class="text-center"><b>@currency($debet_1+$debet_2)</b></td>
            <td class="text-center"><b>@currency($kredit_1+$kredit_2)</b></td>
          </tr>
      </tbody>
     </table>
  </div>
  @endif
  <script>
    function getFrom(){
        let yearPeriode = document.getElementById("from").value;
        document.getElementById("from-text").value = yearPeriode;
    }
    function getTo(){
        let yearPeriode = document.getElementById("to").value;
        document.getElementById("to-text").value = yearPeriode;
    }

  </script>
  <style>
	table, th, td {
        border: 1px solid black;
        border-collapse: collapse;

    }
  </style>

@endsection
