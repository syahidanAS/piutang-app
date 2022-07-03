@extends('main')

@section('title', 'Rekap Umur Piutang')

@section('breadcrumbs')
<div class="breadcrumbs card-header">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Rekap Umur Piutang</h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="card mx-2">
	<div class="card-body">
	  <p class="card-title">Filter Tahun Rekap Umur Piutang</p>

      <div style="float:left;">
        <form action="/rekap-umur-piutang-after" method="POST">
            @csrf
            <?php $years = range(2017, strftime("%Y", time())); ?>
            <div class="row">
                <div class="col">
                    <select class="form-control" name="tahun" onchange="getValue()" id="year-periode" style="font-size: 18px; ">
                        <option value="choose">Pilih Tahun</option>
                        <?php foreach($years as $year) : ?>
                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                      <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-1">
                    <button class="btn btn-info" type="submit"><i class="fa fa-filter" ></i> Filter</button>
                </div>
              </div>
        </form>
      </div>

  <div style="margin-left: 35%">
    <form action="/cetak-umur-piutang" method="POST" target="_blank">
        @csrf
        <input  type="number" id="year-periode2" name="tahun" hidden required>
        <button class="btn btn-success" type="submit"><i class="fa fa-print" onclick="search()"></i> Cetak</button>
    </form>
  </div>


	</div>
  </div>
  @if(count($umur) > 0)
  <div class="card mx-2">
	<div class="card-header">
		<h4 id="periodeValue">Rekap Umur Piutang Tahun {{$tahun}}</h4>
	</div>
	<div class="card-body table-responsive
    ">
        <?php $sum_nominal_piutang = 0 ?>
        <?php $column_five_percent = 0 ?>
        <?php $column_ten_percent = 0 ?>
        <?php $column_fifty_percent = 0 ?>
        <?php $column_hundred_percent = 0 ?>
        <?php $column_grand_total = 0 ?>
		<table class="text-center table table-striped" id="age-table">
            <thead>
                <tr>
					<th>No</th>
					<th>No. Invoice</th>
					<th>Debitur</th>
					<th>Nominal Piutang</th>
					<th>Umur Piutang (Hari)</th>
					<th>0-30 Hari (5%)</th>
					<th>31-60 Hari (10%)</th>
					<th>61-90 Hari (50%)</th>
					<th>>90 Hari (100%)</th>
					<th>Jumlah Penyusutan</th>
				</tr>
            </thead>
                @foreach ($umur as $item)
               <tbody>
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $item->no_invoice }}</td>
                    <td>{{ $item->nm_debitur }}</td>
                    <td>@currency($item->nominal_piutang)</td>
                    <td>{{ $item->umur_piutang }}</td>
                    @if($item->umur_piutang >= 0 && $item->umur_piutang <= 30)
                    <td>@currency($item->hasil_persentase)</td>
                    <td></td>
                    <td></td>

                    <?php $column_five_percent += $item->hasil_persentase  ?>
                    <td>@currency($item->hasil_persentase)</td>
                    @elseif($item->umur_piutang >= 30 && $item->umur_piutang <= 60)
                    <td></td>
                    <td>@currency($item->hasil_persentase)</td>
                    <td></td>
                    <td></td>

                    <td>@currency($item->hasil_persentase)</td>
                    <?php $column_ten_percent += $item->hasil_persentase  ?>
                    @elseif($item->umur_piutang >= 60 && $item->umur_piutang <= 90)
                    <td></td>
                    <td></td>
                    <td>@currency($item->hasil_persentase)</td>
                    <td></td>

                    <td>@currency($item->hasil_persentase)</td>
                    <?php $column_fifty_percent += $item->hasil_persentase  ?>
                    @elseif($item->umur_piutang >= 90)
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>@currency( $item->hasil_persentase)</td>
                    <td>@currency($item->hasil_persentase)</td>
                    <?php $column_hundred_percent += $item->hasil_persentase  ?>
                    @endif
                </tr>
               </tbody>
                <?php $sum_nominal_piutang += $item->nominal_piutang  ?>
                <?php $column_grand_total += $item->hasil_persentase  ?>
                @endforeach

                <tr>
                    <th colspan="3">TOTAL</th>
                    <th>@currency($sum_nominal_piutang)</th>
                    <th> </th>
                    <th>@currency($column_five_percent)</th>
                    <th>@currency($column_ten_percent)</th>
                    <th>@currency($column_fifty_percent)</th>
                    <th>@currency($column_hundred_percent)</th>
                    <th>@currency($column_grand_total)</th>
                </tr>
		</table>
	</div>
  </div>
  <div class="card pull-right mr-3 py-2 px-2 border border-dark">
    <b>TOTAL CADANGAN KERUGIAN PIUTANG: @currency($column_grand_total)</b>
</div>
  @else
  <div class="card mx-2">
	<div class="card-header">
		<h4 id="periodeValue">Rekap Umur Piutang Tahun {{$tahun}}</h4>
	</div>
	<div class="card-body text-center">
        <p>Data umur piutang pada tahun {{$tahun}} tidak ditemukan</p>
    </div>
  </div>
  @endif
  <script>

    function getValue(){
        let yearPeriode = document.getElementById("year-periode").value;
        document.getElementById("year-periode2").value = yearPeriode;
    }





    let url = '{{ url("") }}'

    function rupiah(angka){
        var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return ribuan;
    }
        function search(){
            let yearPeriode = document.getElementById("year-periode").value;
            axios.get(url+'/rekap-umur-piutang-data?tahun='  + yearPeriode)
                .then(function (response) {
                    console.log(response.data);
                    buildTable(response.data);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
        }


        function buildTable(response) {
            var table = document.getElementById('age-table')
            let extraRow;
            $('#age-table tbody').empty();
            for (var i = 0; i < response.length; i++) {

                let five_before, ten_before, fifty_before, hundred_before;

                if(response[i].umur_piutang >= 0 && response[i].umur_piutang <= 30){
                    five_before = "Rp " + rupiah(Math.round(response[i].hasil_persentase));
                    ten_before = "";
                    fifty_before = "";
                    hundred_before ="";
                }else if(response[i].umur_piutang >= 31 && response[i].umur_piutang <= 60){
                    five_before = "";
                    ten_before = "Rp " + rupiah(Math.round(response[i].hasil_persentase));
                    fifty_before = "";
                    hundred_before ="";

                }else if(response[i].umur_piutang >= 61 && response[i].umur_piutang <= 90){
                    five_before = "";
                    ten_before = "";
                    fifty_before = "Rp " + rupiah(Math.round(response[i].hasil_persentase));
                    hundred_before ="";

                }else if(response[i].umur_piutang >= 90){
                    five_before = "";
                    ten_before = "";
                    fifty_before = "";
                    hundred_before = "Rp " + rupiah(Math.round(response[i].hasil_persentase));

                }

                let penyusutan = rupiah(Math.round(response[i].hasil_persentase));


                let tr_open = "<tr>";
                let td_nourut = `<td>${i+1}</td>`;
                let td_noinvoice = ` <td>${response[i].no_invoice}</td>`;
                let td_nmdebitur = ` <td>${response[i].nm_debitur}</td>`;
                let td_nominalpiutang = `<td>Rp ${rupiah(response[i].nominal_piutang)}</td>`;
                let td_umurpiutang = `<td>${response[i].umur_piutang}</td>`
                let td_five = `<td>${five_before}</td>`;
                let td_ten = `<td>${ten_before}</td>`;
                let td_fifty = `<td>${fifty_before}</td>`;
                let td_hundred = `<td> ${hundred_before}</td>`;
                let td_penyusutan = `<td>Rp ${penyusutan}</td>`;
                let tr_close = "</tr>";
                let new_tr = "<tr>";
                let th_total = "<th colspan='4'>TOTAL</th>";
                let new_tr_close = "</tr>";


                table.innerHTML += tr_open + td_nourut + td_noinvoice + td_nmdebitur + td_nominalpiutang + td_umurpiutang + td_five + td_ten + td_fifty + td_hundred + td_penyusutan + tr_close + new_tr + th_total + new_tr_close;
            }
        }

  </script>

  <style>

  </style>
@endsection
