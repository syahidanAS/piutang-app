<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kwitansi Pembayaran</title>
</head>
<body>

        {{-- <div class="wrap-content">
            <div class="kop-surat">
                <img src="{{ asset('images/kop-surat.png') }}" alt="">
            </div>
            <div class="main-content">
                <h4>KWITANSI PEMBAYARAN</h4>
            </div>

        </div> --}}

        <div class="clearfix" id="clearfix">
            <img class="img2" src="{{ $pic }}" alt="Pineapple" height="378">
            <div class="title-container">
                <h1 class="title"><b>KWITANSI PEMBAYARAN</b></h1>
                <P class="receipt-number">{{$no_kwitansi}}</P>
            </div>
            <div style="padding-left:100px;">
                <table style="width: 90%; margin-right:100px;">
                    <tr>
                        <td><b>Telah Diterima Dari</b></td>
                        <td><b>:</b></td>
                        <td style="border-bottom: 1px solid black; padding: 1px 0 0;">{{$nm_debitur}}</td>
                    </tr>
                    <tr>
                        <td><b>Uang Sejumlah</b></td>
                        <td><b>:</b></td>
                        <td style="border-bottom: 1px solid black; padding: 1px 0 0;">{{$uang_sejumlah}}</td>
                    </tr>
                    <tr>
                        <td><b>Untuk Pembayaran</b></td>
                        <td><b>:</b></td>
                        <td style="border-bottom: 1px solid black; padding: 1px 0 0;">{{$keterangan}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black; padding: 1px 0 0;">Periode Bulan {{$periode}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black; padding: 1px 0 0;">di RS Khusus Paru Kabupaten Karawang</td>
                    </tr>
                </table>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="column">
                  <table style="width: 100%">
                    <tr>
                      <td>Transfer Ke</td>
                      <td>:</td>
                      <td>Bank BJB</td>
                    </tr>
                    <tr>
                      <td>Nomor Rekening</td>
                      <td>:</td>
                      <td>0105461331001</td>
                    </tr>
                    <tr>
                      <td>Atas Nama</td>
                      <td>:</td>
                      <td>RSK Paru Karawang</td>
                    </tr>
                  </table>
                   <div class="parallelogram" style="background-color:#bebebe; margin-left:60px; padding-top:4px; padding-bottom:4px; padding-left:10px; ">
                    <span class="nominalpembayaran"><em>@currency($total_pembayaran)</em></span>
                </div>

                </div>
                <div class="column" style="text-align:center;">
                    Karawang, {{$tgl_pembayaran}}
                    <br>
                    Ka. Subbag Keuangan
                    <br><br><br><br><br>
                    <u>(WIKE WIDURI, SKM)</u>
                    <br>
                    NIP. 19811120 200501 2 013
                </div>

              </div>

          </div>


</body>
</html>

<style>
    .nominalpembayaran{
        font-style: italic;
    }

.parallelogram {
    margin-top: 20px;
   -webkit-transform: skew(20deg);
   -moz-transform: skew(20deg);
   -o-transform: skew(20deg);
   transform: skew(20deg);
}

* {
  box-sizing: border-box;
}

.row {
  margin-left:20%;
  margin-right:-10px;
}

.column {
  float: left;
  width: 54%;
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}


.nominal{
    text-align: center;
    background-color:#bbb8b8;
    margin-left:33%;
    padding:1px;
}


#clearfix{
    width:700px;
    border: 1px solid #000;
    padding: 5px;
}
.title-container{
    text-align: center;
}
.title{
    font-size: 1.2rem;
    text-decoration: underline;
}
.receipt-number{
    font-size: 0.8rem;
}
.img2 {
  float: left;
  filter: gray; /* IE6-9 */
  -webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */
  filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
}

.clearfix::after {
  content: "";
  clear: both;
  display: table;
}
</style>
