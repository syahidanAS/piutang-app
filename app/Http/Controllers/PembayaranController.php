<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaranModel;
use App\Models\InvoiceModel;
use App\Models\PembayaranModel;
use App\Models\PiutangModel;
use App\Models\JurnalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class PembayaranController extends Controller
{
    public function tester(Request $request){
        $response = DetailPembayaranModel::selectRaw('detail_pembayaran.sisa_tagihan-detail_pembayaran.total_pembayaran AS status')
                ->join('pembayaran', 'detail_pembayaran.id_pembayaran','pembayaran.id')
                ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
                ->where('piutang.id', $request->id_piutang)
                ->orderBy('detail_pembayaran.id','desc')
                ->first();

        if((int)$response->status <= 0){
            $status ="lunas";
        }else{
            $status = "belum lunas";
        }

        return $status;
    }
    public function index(){
        // $pembayaran = PembayaranModel::selectRaw('piutang.id,no_invoice,tgl_tempo, debitur.nm_debitur, SUM(invoices.qty*jenis_pengobatan.harga) AS total')
        //     ->join('debitur', 'piutang.id_debitur', 'debitur.id')
        //     ->join('invoices', 'piutang.id', 'invoices.id_piutang')
        //     ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
        //     ->groupBy('piutang.id')
        //     ->get();

        $getInvoice = PiutangModel::selectRaw('piutang.id,no_invoice, debitur.nm_debitur')
            ->join('debitur', 'piutang.id_debitur', 'debitur.id')
            ->where('isLocked', false)
            ->get();


        $pembayaran = PembayaranModel::selectRaw('pembayaran.total_pembayaran,id_piutang,pembayaran.id,total_tagihan,piutang.tgl_tempo,nm_debitur,no_invoice')
            ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
            ->join('debitur', 'piutang.id_debitur', 'debitur.id')
            ->get();
        // $status = PembayaranModel::selectRaw('SUM(total_pembayaran) AS dibayarkan')
        //     ->groupBy('id_piutang')
        //     ->get();

		// $pembayaran = DB::select("
		// SELECT pembayaran.id,id_piutang,no_pembayaran,total_pembayaran,sisa_tagihan,no_invoice,nm_debitur,piutang.tgl_tempo,pembayaran.status FROM
		// pembayaran JOIN piutang on pembayaran.id_piutang=piutang.id
        // JOIN debitur on piutang.id_debitur=debitur.id
        // WHERE pembayaran.id IN(SELECT MAX(pembayaran.id) FROM pembayaran GROUP BY pembayaran.id_piutang);");
        return view('pembayaran.index',compact(
            'pembayaran',
            'getInvoice'
        ));
    }

    public function getTotalTagihan(Request $request){
        $tagihan = InvoiceModel::selectRaw('sum(jenis_pengobatan.harga*invoices.qty) AS total_tagihan')
        ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
        ->where('id_piutang', $request->id_piutang)->get();


        return $tagihan;
    }

    public function getLastJournalId(Request $request){
    }

    public function storePayment(Request $request){


        $totalPembayaranFix = DetailPembayaranModel::selectRaw('sum(detail_pembayaran.total_pembayaran) AS total_pembayaran')
        ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
        ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
        ->where('piutang.id', $request->id_piutang)->get();

        $payload = [
           "id_piutang" => $request->id_piutang,
            "total_tagihan" => $request->total_tagihan,
            "total_pembayaran" => $totalPembayaranFix
        ];

        $result = PembayaranModel::create(
            $payload
        );

        $detail_pembayaran_payload = [
            "no_pembayaran" => "pembayaran-default",
            "id_pembayaran" => $result->id,
            "tgl_pembayaran" => date('Y-m-d H:i:s'),
            "total_pembayaran" => 0,
            "sisa_tagihan" => $request->total_tagihan,
            "status" => "belum lunas",
        ];

        $detail_pembayaran = DetailPembayaranModel::create(
            $detail_pembayaran_payload
        );

            if($result){
                $payloadUpdate =[
                    "isLocked" => true
                ];
                PiutangModel::where('id', $request->id_piutang)->update($payloadUpdate);


                $getLastJurnalId = JurnalModel::selectRaw('id')->orderBy('id', 'desc')->skip(1)->take(1)->first();

                $getDebiturName = PiutangModel::selectRaw('debitur.nm_debitur')
                ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                ->where('piutang.id', $request->id_piutang)
                ->first();

                if(!$getLastJurnalId){
                    $lastJurnalId = 0+1;
                }else{
                    $lastJurnalId = $getLastJurnalId['id']+1;
                }


                // $request->tgl_pengajuan;

                $jurnalPayload = [
                [
                    "id_piutang" => $request->id_piutang,
                    "no_jurnal" => "J".str_pad($lastJurnalId, 4, '0', STR_PAD_LEFT),
                    "tanggal"=> $request->tgl_pengajuan,
                    "keterangan" => "Piutang ". $getDebiturName["nm_debitur"],
                    "kode_perkiraan" => "2.1",
                    "flag" => "piutang-pendapatan",
                    "nama_perkiraan" => "piutang",
                    "nominal" => $request->total_tagihan
                ],
                [
                    "id_piutang" => $request->id_piutang,
                    "no_jurnal" => "J".str_pad($lastJurnalId, 4, '0', STR_PAD_LEFT),
                    "tanggal"=> $request->tgl_pengajuan,
                    "keterangan" => "Piutang ". $getDebiturName["nm_debitur"],
                    "kode_perkiraan" => "4.1",
                    "flag" => "pendapatan-piutang",
                    "nama_perkiraan" => "pendapatan",
                    "nominal" => $request->total_tagihan
                ],
            ];
                $postJurnal = JurnalModel::insert($jurnalPayload);
                return redirect('/invoice?id='.$request->id_piutang)->with('success', "Berhasil posting pembayaran");


            }else{
                return redirect('/invoice?id='.$request->id_piutang)->with('fail', "Gagal posting pembayaran");
            }

    }

    public function status_checker(Request $request){
        $checker= PembayaranModel::where('id_piutang', $request->id_piutang)->first();
        if(!$checker){
            $payload = [
                "status" => "ok",
                "data" =>"Belum Lunas"
            ];
        }else{
            $getTagihan = InvoiceModel::selectRaw('SUM(invoices.qty*jenis_pengobatan.harga) AS total')
                    ->join('jenis_pengobatan', 'invoices.id_layanan', 'jenis_pengobatan.id')
                    ->where('id_piutang',$request->id_piutang)
                    ->first();
            $getPembayaran = PembayaranModel::selectRaw('SUM(total_pembayaran) AS total_pembayaran')
                    ->where('id_piutang', $request->id_piutang)
                    ->first();

            $tagihan = $getTagihan["total"];
            $pembayaran = $getPembayaran["total_pembayaran"];

            if($pembayaran == $tagihan){
                $payload = [
                    "status" => "ok",
                    "data" =>"Lunas"
                ];
            }else if ($pembayaran >= $tagihan){
                $payload = [
                    "status" => "ok",
                    "data" =>"Lunas"
                ];
            }else{
                $payload = [
                    "status" => "ok",
                    "data" =>"On Process"
                ];
            }

        }
        return $payload;
    }

    public function destroy_detail(Request $request){
        $id_detail_pembayaran = $request->id;
        $id_piutang = $request->id_piutang;
        $total_tagihan = $request->total_tagihan;
        $total_pembayaran = $request->total_pembayaran;
        $id_pembayaran = $request->id_pembayaran;

        $getTotalPembayaran = PembayaranModel::selectRaw('total_pembayaran')
            ->where('id_piutang', $id_piutang)
            ->first();
        $finalPembayaranTerakhir = $getTotalPembayaran['total_pembayaran']-$total_pembayaran;

        $payloadUpdate = [
            "total_pembayaran" => $finalPembayaranTerakhir
        ];

        PembayaranModel::where('id_piutang', $id_piutang)
                    ->update($payloadUpdate);

        $result = DetailPembayaranModel::destroy($id_detail_pembayaran);

        $response = DetailPembayaranModel::selectRaw('detail_pembayaran.sisa_tagihan-detail_pembayaran.total_pembayaran AS status')
        ->join('pembayaran', 'detail_pembayaran.id_pembayaran','pembayaran.id')
        ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
        ->where('piutang.id', $request->id_piutang)
        ->orderBy('detail_pembayaran.id','desc')
        ->first();

        if((int)$response->status <= 0){
            $status ="Lunas";
        }else{
            $status = "Belum Lunas";
        }

        $payloadUpdateStatus =[
            "status_piutang" => $status
        ];
        PiutangModel::where('id', $request->id_piutang)->update($payloadUpdateStatus);


        if ($result) {
            return redirect('/detail-pembayaran?id_piutang='.$id_piutang.'&total_tagihan='.$total_tagihan.'&id_pembayaran='.$id_pembayaran)->with('success', "Berhasil menghapus data, silahkan coba lagi!!");
        } else {
            return redirect('/detail-pembayaran?id_piutang='.$id_piutang.'&total_tagihan='.$total_tagihan.'&id_pembayaran='.$id_pembayaran)->with('failed', "Gagal menghapus data, silahkan coba lagi!!");
        }
    }

    public function detail_pembayaran(Request $request){

        $getPembayaran = DetailPembayaranModel::selectRaw('detail_pembayaran.id,no_pembayaran,tgl_pembayaran,id_pembayaran,detail_pembayaran.total_pembayaran,piutang.no_invoice,sisa_tagihan,status,tgl_pengajuan, debitur.nm_debitur')
            ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
            ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
            ->join('debitur', 'piutang.id_debitur', 'debitur.id')
            ->where('pembayaran.id_piutang', $request->id_piutang)
            ->get();
        $totalTagihan = $request->total_tagihan;


        $checkerLastPayment = DetailPembayaranModel::orderBy('detail_pembayaran.id','desc')
        ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
        ->where('pembayaran.id_piutang', $request->id_piutang)
        ->limit(1)
        ->first();

        $lastPayment = DetailPembayaranModel::selectRaw('detail_pembayaran.no_pembayaran,tgl_pembayaran,piutang.no_invoice,debitur.nm_debitur,detail_pembayaran.sisa_tagihan,detail_pembayaran.total_pembayaran')
        ->orderBy('detail_pembayaran.id','desc')
        ->join('pembayaran', 'detail_pembayaran.id_pembayaran', 'pembayaran.id')
        ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
        ->join('debitur', 'piutang.id_debitur', 'debitur.id')
        ->where('id_piutang', $request->id_piutang)
        ->limit(1)
        ->get();

        // return $lastPayment;

        if(!$checkerLastPayment){
            $resultLastPayment = $request->total_tagihan;
        }else{
            $totalTagihan =  $lastPayment[0]['sisa_tagihan'];
            $totalPembayaran =  $lastPayment[0]['total_pembayaran'];
            $resultLastPayment = $totalTagihan-$totalPembayaran;
        }
        $idPembayaran = $request->id_pembayaran;
        $idPiutang = $request->id_piutang;
        $tagihan = $request->total_tagihan;
        return view('detail_pembayaran.index', compact(
            'getPembayaran',
            'totalTagihan',
            'idPembayaran',
            'idPiutang',
            'tagihan',
            'resultLastPayment'
        ));
    }



    function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function store(Request $request){
        $id_pembayaran = $request->id_pembayaran;
        $id_piutang = $request->id_piutang;
        $total_pembayaran = $request->total_pembayaran;
        $tanggal_pembayaran = $request->tanggal_pembayaran;

        $getPembayaran = DetailPembayaranModel::where('id_pembayaran', $id_pembayaran)
        ->first();
        if(!$getPembayaran){
            $tagihan = $request->total_tagihan;
            if((int)$total_pembayaran <= (int)$tagihan){
                $status_pembayaran = "Belum Lunas";
            }else if((int)$total_pembayaran >= (int)$tagihan || (int)$total_pembayaran >= (int)$tagihan){
                $status_pembayaran = "Lunas";
            }
            $payload = [
                "id_pembayaran" => $id_pembayaran,
                "total_pembayaran" => $total_pembayaran,
                "tgl_pembayaran" => $tanggal_pembayaran,
                "sisa_tagihan" => $request->total_tagihan,
                "status" => $status_pembayaran
            ];
        }else{
            $lastPayment = DetailPembayaranModel::where('id_pembayaran', $request->id_pembayaran)
            ->orderBy('id','desc')
            ->limit(1)
            ->get();

            $totalTagihan =  $lastPayment[0]['sisa_tagihan'];
            $totalPembayaran =  $lastPayment[0]['total_pembayaran'];
            $resultPayment = $totalTagihan - $totalPembayaran;

            if((int)$total_pembayaran >= (int)$resultPayment){
                $status = "Lunas";
            }else if((int)$total_pembayaran <= (int)$resultPayment){
                $status = "Belum Lunas";
            }else{
                $status = "Belum Lunas";
            }
            $payload = [
                "id_pembayaran" => $id_pembayaran,
                "total_pembayaran" => $total_pembayaran,
                "tgl_pembayaran" => $tanggal_pembayaran,
                "sisa_tagihan" => $resultPayment,
                "status" => $status
            ];
        }

        $payloadUpdateStatus =[
            "status_piutang" => $status
        ];
        PiutangModel::where('id', $request->id_piutang)->update($payloadUpdateStatus);

        $result = DetailPembayaranModel::create(
            $payload
        );

        $length = 4;

        $payloadUpdate = [
            'no_pembayaran' => '900/'.substr(str_repeat(0, $length).$result->id, - $length).'/KEU/'.$this->numberToRomanRepresentation(date("m", strtotime($request->input('tanggal_pembayaran')))).'/'.date("Y", strtotime($request->input('tanggal_pembayaran'))),
        ];

        $resultUpdate = DetailPembayaranModel::where('id', $result->id)
                    ->update($payloadUpdate);

        // return $result->id;


                $getPreviousValue = PembayaranModel::where('id', $id_pembayaran)->get();
                $payloadUpdate = [
                    "total_pembayaran" => $request->total_pembayaran+$getPreviousValue[0]['total_pembayaran']
                ];


                $updatePembayaran = PembayaranModel::where('id',$request->id_pembayaran)
                    ->update($payloadUpdate);

                if($updatePembayaran){


                    // INI BARIS KODE YANG BARU
                    $paymentChecker = PembayaranModel::selectRaw('pembayaran.total_tagihan,total_pembayaran')
                    ->join('piutang', 'pembayaran.id_piutang', 'piutang.id')
                    ->where('piutang.id', $request->id_piutang)
                    ->first();

                    $resultPayment = $paymentChecker["total_tagihan"] - $paymentChecker["total_pembayaran"];

                    if($resultPayment <= 0){

                        //PROSES POST DATA KE JURNAL
                        $getLastJurnalId = JurnalModel::selectRaw('id')->orderBy('id', 'desc')->skip(1)->take(1)->first();
                        $getDebiturName = PiutangModel::selectRaw('debitur.nm_debitur')
                        ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                        ->where('piutang.id', $request->id_piutang)
                        ->first();

                        if(!$getLastJurnalId){
                            $lastJurnalId = 0+1;
                        }else{
                            $lastJurnalId = $getLastJurnalId['id']+1;
                        }

                        $jurnalPayload = [
                        [
                            "id_piutang" => $request->id_piutang,
                            "no_jurnal" => "J".str_pad($lastJurnalId, 4, '0', STR_PAD_LEFT),
                            "keterangan" => "Pelunasan ". $getDebiturName["nm_debitur"],
                            "tanggal" => $tanggal_pembayaran,
                            "kode_perkiraan" => "1.1",
                            "flag" => "kas-piutang",
                            "nama_perkiraan" => "kas",
                            "nominal" => $total_pembayaran
                        ],
                        [
                            "id_piutang" => $request->id_piutang,
                            "no_jurnal" => "J".str_pad($lastJurnalId, 4, '0', STR_PAD_LEFT),
                            "keterangan" => "Pelunasan ". $getDebiturName["nm_debitur"],
                            "tanggal" => $tanggal_pembayaran,
                            "kode_perkiraan" => "2.1",
                            "flag" => "piutang-kas",
                            "nama_perkiraan" => "piutang",
                            "nominal" => $total_pembayaran
                        ],
                    ];
                        $postJurnal = JurnalModel::insert($jurnalPayload);

                        // AKHIR DARI PROSES POST DATA KE JURNAL


                    }else{

                        //PROSES POST DATA KE JURNAL
                        $getLastJurnalId = JurnalModel::selectRaw('id')->orderBy('id', 'desc')->skip(1)->take(1)->first();
                        $getDebiturName = PiutangModel::selectRaw('debitur.nm_debitur')
                        ->join('debitur', 'piutang.id_debitur', 'debitur.id')
                        ->where('piutang.id', $request->id_piutang)
                        ->first();

                        if(!$getLastJurnalId){
                            $lastJurnalId = 0+1;
                        }else{
                            $lastJurnalId = $getLastJurnalId['id']+1;
                        }

                        $jurnalPayload = [
                        [
                            "id_piutang" => $request->id_piutang,
                            "no_jurnal" => "J".str_pad($lastJurnalId, 4, '0', STR_PAD_LEFT),
                            "keterangan" => "Pembayaran ". $getDebiturName["nm_debitur"],
                            "tanggal" => $tanggal_pembayaran,
                            "kode_perkiraan" => "1.1",
                            "flag" => "kas-piutang",
                            "nama_perkiraan" => "kas",
                            "nominal" => $total_pembayaran
                        ],
                        [
                            "id_piutang" => $request->id_piutang,
                            "no_jurnal" => "J".str_pad($lastJurnalId, 4, '0', STR_PAD_LEFT),
                            "keterangan" => "Pembayaran ". $getDebiturName["nm_debitur"],
                            "tanggal" => $tanggal_pembayaran,
                            "kode_perkiraan" => "2.1",
                            "flag" => "piutang-kas",
                            "nama_perkiraan" => "piutang",
                            "nominal" => $total_pembayaran
                        ],
                    ];
                        $postJurnal = JurnalModel::insert($jurnalPayload);

                        // AKHIR DARI PROSES POST DATA KE JURNAL
                    }

















                    return redirect('/detail-pembayaran?id_piutang='.$request->id_piutang.'&total_tagihan='.$request->total_tagihan.'&id_pembayaran='.$request->id_pembayaran)->with('success', "Berhasil menambahkan data");
                }else{
                    return redirect('/detail-pembayaran?id_piutang='.$request->id_piutang.'&total_tagihan='.$request->total_tagihan.'&id_pembayaran='.$request->id_pembayaran)->with('fail', "Gagal menambahkan data");
                }
    }
}
