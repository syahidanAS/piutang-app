<img class="img2" src="{{ $pic }}" alt="Pineapple">
<h1 style="text-align:center; margin-bottom:30px; font-size:1.5rem;">REKAPITULASI UMUR PIUTANG TAHUN {{$tahun}}</h1>
<div class="card-body">
    <?php $sum_nominal_piutang = 0 ?>
    <?php $column_five_percent = 0 ?>
    <?php $column_ten_percent = 0 ?>
    <?php $column_fifty_percent = 0 ?>
    <?php $column_hundred_percent = 0 ?>
    <?php $column_grand_total = 0 ?>
    <table id="age-table" style="text-align: center;">
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
                <th></th>
                <th>@currency($column_five_percent)</th>
                <th>@currency($column_ten_percent)</th>
                <th>@currency($column_fifty_percent)</th>
                <th>@currency($column_hundred_percent)</th>
                <th>@currency($column_grand_total)</th>
            </tr>
    </table>
</div>
<div style="margin-top: 20px; font-size:1.1rem; text-align:center; border-style:solid; margin-left:56%; padding-top:5px; padding-bottom:5px; padding-right:5px; margin-right:33px;">
    TOTAL CADANGAN KERUGIAN PIUTANG: @currency($column_grand_total)
</div>
<style>
	table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    .img2{
        display: block;
        margin-left: 6%;
        margin-bottom: 30px;
        width: 85%;
        height: 25%;
    }
  </style>
