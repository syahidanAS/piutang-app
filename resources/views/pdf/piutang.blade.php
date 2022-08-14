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
