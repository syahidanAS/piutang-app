<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jurnal Umum</title>
</head>
<body>
    <img class="img2" src="{{ $pic }}" alt="Pineapple">
    <h1 style="text-align:center; margin-bottom:30px; font-size:1.5rem;">JURNAL UMUM PERIODE {{$from}} s.d {{$to}}</h1>

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
            @foreach ($jurnal as $key=>$item)
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

       <style>
        table, th, td {
            width: 100%;
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
</body>
</html>
