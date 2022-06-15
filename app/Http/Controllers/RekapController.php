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







    public function getRekapPiutangBulanLalu(Request $request){
        $date_1 = $request->date_1;
        $date_2 = $request->date_2;
        $date_3 = $request->date_3;
        $date_4 = $request->date_4;
        $debitur = DebiturModel::get();

        if($request->debiturId[0] == "all"){
            $resultBulanLalu = DetailPembayaranModel::selectRaw('detail_pembayaran.no_pembayaran,piutang.no_invoice, debitur.nm_debitur,max(detail_pembayaran.sisa_tagihan)-SUM(detail_pembayaran.total_pembayaran) AS piutang_bulan_lalu')
                    ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
                    ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
                    ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                    ->join('invoices', 'piutang.id', 'invoices.id_piutang')
                    ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
                    ->whereBetween('detail_pembayaran.tgl_pembayaran', [$date_1, $date_2])
                    ->groupBy('piutang.id')
                    ->get();

            $resultBulanIni = DetailPembayaranModel::selectRaw('detail_pembayaran.no_pembayaran,piutang.no_invoice, debitur.nm_debitur,max(detail_pembayaran.sisa_tagihan)-SUM(detail_pembayaran.total_pembayaran) AS piutang_bulan_ini')
                    ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
                    ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
                    ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                    ->join('invoices', 'piutang.id', 'invoices.id_piutang')
                    ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
                    ->whereBetween('detail_pembayaran.tgl_pembayaran', [$date_3, $date_4])
                    ->groupBy('piutang.id')
                    ->get();

        }else{
            $resultBulanLalu = DetailPembayaranModel::selectRaw('detail_pembayaran.no_pembayaran,piutang.no_invoice, debitur.nm_debitur,max(detail_pembayaran.sisa_tagihan)-SUM(detail_pembayaran.total_pembayaran) AS piutang_bulan_lalu')
                    ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
                    ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
                    ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                    ->join('invoices', 'piutang.id', 'invoices.id_piutang')
                    ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
                    ->whereBetween('detail_pembayaran.tgl_pembayaran', [$date_1, $date_2])
                    ->whereIn('debitur.id', $request->debiturId)
                    ->groupBy('piutang.id')
                    ->get();

            $resultBulanIni = DetailPembayaranModel::selectRaw('detail_pembayaran.no_pembayaran,piutang.no_invoice, debitur.nm_debitur,max(detail_pembayaran.sisa_tagihan)-SUM(detail_pembayaran.total_pembayaran) AS piutang_bulan_ini')
                    ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
                    ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
                    ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                    ->join('invoices', 'piutang.id', 'invoices.id_piutang')
                    ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
                    ->whereBetween('detail_pembayaran.tgl_pembayaran', [$date_3, $date_4])
                    ->whereIn('debitur.id', $request->debiturId)
                    ->groupBy('piutang.id')
                    ->get();
        }
        $flag = "after-search";
        return view('rekap.piutang', compact('debitur','flag','resultBulanLalu','resultBulanIni'));
    }
}
