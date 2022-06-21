<?php

namespace App\Http\Controllers;

use App\Models\DebiturModel;
use App\Models\DetailPembayaranModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function rekap_piutang(){
        $debitur = DebiturModel::get();
        $flag = "before-search";
        return view('rekap.piutang', compact('flag','debitur'));
    }


    public function rekap_umur_piutang(){
        $flag="after-search";
        $tahun = date("Y");
        $umur = DB::select("
        SELECT piutang.no_invoice, debitur.nm_debitur, pembayaran.total_tagihan-pembayaran.total_pembayaran AS nominal_piutang, IF(DATEDIFF(now(),piutang.tgl_tempo) <= 0, 0,DATEDIFF(now(),piutang.tgl_tempo)) AS umur_piutang,
        IF(DATEDIFF(now(),piutang.tgl_tempo) <= 0,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*5/100,
        IF(DATEDIFF(now(),piutang.tgl_tempo) <= 30,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*5/100,
            IF(DATEDIFF(now(),piutang.tgl_tempo) >= 30,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*10/100,
            IF(DATEDIFF(now(),piutang.tgl_tempo) <= 60,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*10/100,
                IF(DATEDIFF(now(),piutang.tgl_tempo) >= 60,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*50/100,
                IF(DATEDIFF(now(),piutang.tgl_tempo) <= 90,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*50/100,
                    IF(DATEDIFF(now(),piutang.tgl_tempo) >= 90,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*100/100,(pembayaran.total_tagihan-pembayaran.total_pembayaran)*100/100) )))))) AS hasil_persentase
        FROM piutang JOIN debitur ON piutang.id_debitur=debitur.id JOIN pembayaran ON piutang.id=pembayaran.id_piutang WHERE piutang.status_piutang='Belum Lunas' AND YEAR(piutang.tgl_tempo)=$tahun;
        ");
        return view('rekap.umur', compact('flag','tahun','umur'));
    }

    public function rekapUmurPiutangAfter(Request $request){
        $flag="after-search";
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
        return view('rekap.umur', compact('flag','tahun','umur'));
    }







    public function getRekapPiutang(Request $request){
        $debiturs = DebiturModel::get();
        $flag = "after-search";
        $debitur = $request->debiturId;
        $from = $request->from;
        $to = $request->to;
        $firstQuery = DetailPembayaranModel::selectRaw("piutang.no_invoice, debitur.nm_debitur,pembayaran.total_tagihan")
                    ->join("pembayaran", "detail_pembayaran.id_pembayaran", "pembayaran.id")
                    ->join("piutang", "pembayaran.id_piutang", "piutang.id")
                    ->join("debitur", "piutang.id_debitur","debitur.id")
                    ->whereBetween("tgl_pembayaran", [$from,$to])
                    ->groupBy('piutang.id')
                    ->get();



        $queryResult = DetailPembayaranModel::selectRaw("piutang.no_invoice, debitur.nm_debitur,pembayaran.total_tagihan, detail_pembayaran.tgl_pembayaran,detail_pembayaran.total_pembayaran AS total_pembayaran, detail_pembayaran.sisa_tagihan-detail_pembayaran.total_pembayaran AS sisa_piutang")
                    ->join("pembayaran", "detail_pembayaran.id_pembayaran", "pembayaran.id")
                    ->join("piutang", "pembayaran.id_piutang", "piutang.id")
                    ->join("debitur", "piutang.id_debitur","debitur.id")
                    ->whereBetween("tgl_pembayaran", [$from,$to])
                    ->orderBy('piutang.id')
                    ->get();

        
        // return view('rekap.piutang', compact('flag','queryResult','debiturs','firstQuery'));
    }
}
