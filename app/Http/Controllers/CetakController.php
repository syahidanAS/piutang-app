<?php

namespace App\Http\Controllers;

use App\Models\InvoiceModel;
use App\Models\PiutangModel;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakController extends Controller
{
    public function printSurat(Request $request){
        $today = Carbon::now()->isoFormat('dddd D MMMM Y');
       $piutang = PiutangModel::selectRaw('piutang.id,no_invoice,tgl_pengajuan,tgl_tempo,id_debitur,debitur.nm_debitur,alamat,  DATEDIFF(NOW(), piutang.tgl_tempo) AS due')
        ->join('debitur', 'piutang.id_debitur', '=', 'debitur.id')
        ->where('piutang.id', $request->id)
        ->get();

        $path = base_path('logo-karawang.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $path2 = base_path('logo.png');
        $type2 = pathinfo($path2, PATHINFO_EXTENSION);
        $data2 = file_get_contents($path2);
        $logo = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

        $pdf = PDF::loadView('pdf.surat', compact('piutang', 'pic', 'logo','today'))->setPaper('legal', 'potrait');;
        return  $pdf->stream('surat-tagihan.pdf',array('Attachment'=>0));
    }

    public function mock(){
        return view('invoice.index');
    }

    public function printInvoice(Request $request){

        $terbilang = $request->input('terbilang');
        $potongan = $request->input('potongan');
        $keterangan_invoice = $request->input('keterangan_invoice');
        $materai = $request->input('materai');

        $piutang = PiutangModel::selectRaw('piutang.id,no_invoice,tgl_pengajuan,tgl_tempo,id_debitur,debitur.nm_debitur,  DATEDIFF(NOW(), piutang.tgl_tempo) AS due')
        ->join('debitur', 'piutang.id_debitur', '=', 'debitur.id')
        ->where('piutang.id',$request->id)
        ->get();

        $invoice = InvoiceModel::selectRaw('invoices.qty, piutang.no_invoice,tgl_pengajuan,tgl_tempo, debitur.nm_debitur,alamat,email_deb,tlp_deb, jenis_pengobatan.kd_layanan,nama_pelayanan,unit_layanan,harga, (invoices.qty*jenis_pengobatan.harga) AS total')
        ->join('piutang', 'invoices.id_piutang', '=', 'piutang.id')
        ->join('debitur', 'piutang.id_debitur', '=', 'debitur.id')
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
        ->where('piutang.id', $request->id)
        ->get();

        $unit = InvoiceModel::selectRaw('jenis_pengobatan.unit_layanan')
        ->groupBy('jenis_pengobatan.unit_layanan')
        ->join('piutang', 'invoices.id_piutang', '=', 'piutang.id')
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')

        ->where('piutang.id', $request->id)
        ->get();


        $total = InvoiceModel::selectRaw('sum(jenis_pengobatan.harga*invoices.qty) AS sub_total')
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
        ->where('invoices.id_piutang', $request->id)
        ->first();

        $grand_total = (int)$total->sub_total+(int)$materai-(int)$potongan;


        $path = base_path('logo-karawang.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $path2 = base_path('logo.png');
        $type2 = pathinfo($path2, PATHINFO_EXTENSION);
        $data2 = file_get_contents($path2);
        $logo = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

        $pdf = PDF::loadView('pdf.invoice', compact('terbilang','potongan','materai','keterangan_invoice','piutang','invoice','unit','total','grand_total','pic', 'logo'))->setPaper('legal', 'potrait');;
        return  $pdf->stream('invoice.pdf',array('Attachment'=>0));
    }

    public function printKwitansi(Request $request){

        $no_kwitansi = $request->no_pembayaran;
        $nm_debitur = $request->nm_debitur;
        $tgl_pembayaran = $request->tgl_pembayaran;
        $total_pembayaran = $request->total_pembayaran;
        $periode = $request->periode;
        $uang_sejumlah = $request->uang_sejumlah;
        $keterangan = $request->keterangan;



        $path = base_path('kop-surat-vertical.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data);


        $pdf = PDF::loadView('pdf.kwitansi', compact('no_kwitansi',
                                                    'nm_debitur',
                                                    'tgl_pembayaran',
                                                    'total_pembayaran',
                                                    'pic',
                                                    'periode',
                                                    'uang_sejumlah',
                                                    'keterangan'))->setPaper('A5', 'landscape');;
        return  $pdf->stream('kwitansi.pdf',array('Attachment'=>0));

    }


    public function printUmurPiutang(Request $request){
        $tahun = $request->tahun;
        $umur = DB::select("
        SELECT piutang.no_invoice, debitur.nm_debitur, pembayaran.total_tagihan-pembayaran.total_pembayaran AS nominal_piutang, IF(DATEDIFF(now(),piutang.tgl_tempo) <= 0, 0,DATEDIFF(now(),piutang.tgl_tempo)) AS umur_piutang,
        IF(DATEDIFF(now(),piutang.tgl_tempo) <= 0,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*5/100,
        IF(DATEDIFF(now(),piutang.tgl_tempo) <= 30,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*5/100,
            IF(DATEDIFF(now(),piutang.tgl_tempo) >= 30,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*10/100,
            IF(DATEDIFF(now(),piutang.tgl_tempo) <= 60,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*10/100,
                IF(DATEDIFF(now(),piutang.tgl_tempo) >= 60,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*50/100,
                IF(DATEDIFF(now(),piutang.tgl_tempo) <= 90,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*50/100,
                    IF(DATEDIFF(now(),piutang.tgl_tempo) >= 90,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*100/100,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*100/100) )))))) AS hasil_persentase
        FROM piutang JOIN debitur ON piutang.id_debitur=debitur.id JOIN pembayaran ON piutang.id=pembayaran.id_piutang WHERE piutang.status_piutang='Belum Lunas' AND YEAR(piutang.tgl_tempo)=$request->tahun;
        ");
        $pdf = PDF::loadView('pdf.umur', compact('umur'))->setPaper('A4', 'landscape');;
        return  $pdf->stream('umur-piutang.pdf',array('Attachment'=>0));
    }
}
