<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Piutang</title>
</head>
<body>
    <img class="img2" src="{{ $pic }}" style="margin-left: 9%">
    <h1 style="text-align:center; margin-bottom:30px; font-size:1.5rem;">REKAPITULASI UMUR PIUTANG TAHUN {{$from}} s.d {{$to}}</h1>
    <table>
        <thead>
            <tr>
                <th>No Invoice</th>
                <th>Nama Debitur</th>
                <th>Piutang s.d Bulan Ini</th>
                <th>Tanggal Pembayaran</th>
                <th>Total Pembayaran</th>
                <th>Sisa Piutang</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($queryResult as $key=>$item)
            <tr>
                <td>{{$item->no_invoice}}</td>
                <td>{{ $item->nm_debitur }}</td>
                <td>@currency($item->total_tagihan)</td>
                <td>{{ $item->tgl_pembayaran->isoFormat('D MMMM Y') }}</td>
                <td>@currency($item->total_pembayaran)</td>
                <td>@currency($item->sisa_piutang)</td>
            </tr>
            @endforeach
        </tbody>
     </table>

<style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      font-size: 1rem;
      width: 100%;
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
</body>
</html>
