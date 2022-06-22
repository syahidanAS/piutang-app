<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaranModel;
use App\Models\InvoiceModel;
use App\Models\PembayaranModel;
use App\Models\PengobatanModel;
use App\Models\PiutangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

	public function tester(Request $request){

	}
    public function index(Request $request){
        $piutang = PiutangModel::selectRaw('piutang.id,no_invoice,tgl_pengajuan,tgl_tempo,id_debitur,debitur.nm_debitur,  DATEDIFF(NOW(), piutang.tgl_tempo) AS due, piutang.isLocked')
        ->join('debitur', 'piutang.id_debitur', '=', 'debitur.id')
        ->where('piutang.id',$request->id)
        ->get();

        $invoice= InvoiceModel::selectRaw('invoices.id,id_piutang,qty, jenis_pengobatan.nama_pelayanan,harga, (invoices.qty*jenis_pengobatan.harga) AS total')
        ->where('id_piutang','like',"%".$request->id."%")
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
        ->get();
        $pelayanan = PengobatanModel::latest()->get();

        $total = InvoiceModel::selectRaw('sum(jenis_pengobatan.harga*invoices.qty) AS sub_total')
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
        ->where('invoices.id_piutang', $request->id)
        ->first();

        $piutangCondition = $piutang[0]['isLocked'];

        $idPiutang = $request->id;

        return view('invoice.index', compact('piutang','pelayanan','invoice','total','piutangCondition','idPiutang'));
    }


    public function api(Request $request){
        $invoice= InvoiceModel::selectRaw('invoices.id,id_piutang,qty, jenis_pengobatan.nama_pelayanan,harga, (invoices.qty*jenis_pengobatan.harga) AS total')
        ->where('id_piutang',$request->id_piutang)
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
        ->get();

        $total = InvoiceModel::selectRaw('sum(jenis_pengobatan.harga*invoices.qty) AS sub_total')
        ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
        ->where('invoices.id_piutang', $request->id_piutang)
        ->first();

        $payload = [
            "invoice" => $invoice,
            $total->sub_total
        ];
        return $payload;
    }

    public function store(Request $request){
        $idPiutangGlobal = $request->input('id_piutang');
        $payload = [
            'id_piutang' =>    $request->input('id_piutang'),
            'id_layanan' => $request->input('id_layanan'),
            'qty' => $request->input('qty')
        ];

        $checker= InvoiceModel::where('id_layanan', $request->id_layanan)->where('id_piutang', $request->id_piutang)->first();

        if($checker){
            return response()->json([
                "status" => "fail",
                "message" => "failed",
            ], 409);
        }else{
            $result = InvoiceModel::create($payload);

            $resultSum= InvoiceModel::selectRaw('SUM(invoices.qty*jenis_pengobatan.harga) AS total')
            ->where('id_piutang','like',"%".$idPiutangGlobal."%")
            ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
            ->first();
            PiutangModel::where('id',$idPiutangGlobal)
            ->update(['total_piutang' => (int)$resultSum->total]);
            if($result){
                return response()->json([
                    "status" => "ok",
                    "message" => "success"
                ], 201);
            }else{
                return response()->json([
                    "status" => "fail",
                    "message" => "failed",
                ], 400);
            }
            return response()->json([
                "status" => "fail",
                "message" => "failed",
            ], 400);
        }
    }


    public function destroy(Request $request){
        $idPiutangGlobal = $request->id_piutang;
        $result = InvoiceModel::destroy($request->input('id'));

        if($result){
            $resultSum= InvoiceModel::selectRaw('SUM(invoices.qty*jenis_pengobatan.harga) AS total')
                ->where('id_piutang','like',"%".$idPiutangGlobal."%")
                ->join('jenis_pengobatan', 'invoices.id_layanan', '=', 'jenis_pengobatan.id')
                ->first();

            $totalPiutang = (int)$resultSum->total - (int)$request->tagihanFix;

            if($totalPiutang < 0){
                $totalPiutang = 0;
            }
            PiutangModel::where('id',$idPiutangGlobal)
                ->update(['total_piutang' => $totalPiutang]);

            return redirect('/invoice?id='.$request->input('id_piutang'))->with('success', "Berhasil menghapus data");
        }else{
            return redirect('/invoice?id='.$request->input('id_piutang'))->with('failed', "Gagal menghapus data, silahkan coba lagi!!");
        }

    }
}
