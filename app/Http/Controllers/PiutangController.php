<?php

namespace App\Http\Controllers;

use App\Models\DebiturModel;
use App\Models\PengobatanModel;
use App\Models\PiutangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class PiutangController extends Controller
{
    public function index(){
        $debitur = DebiturModel::latest()->get();
        $piutang = PiutangModel::with('debitur')->get();
        return view('piutang.index',compact(
            'piutang',
            'debitur'
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
    public function api(Request $request){
        $piutang = PiutangModel::selectRaw('piutang.id,total_piutang,no_invoice,tgl_pengajuan,tgl_tempo,id_debitur,debitur.nm_debitur,status_piutang,  DATEDIFF(NOW(), piutang.tgl_tempo) AS due')
                    ->join('debitur', 'piutang.id_debitur', '=', 'debitur.id')
                    ->where('no_invoice','like',"%".$request->no_invoice."%")
                    ->orWhere('nm_debitur','like',"%".$request->no_invoice."%")
                    ->get();
                    return $piutang;
    }
    public function store(Request $request){
        $length = 4;
            $payload = [
                'no_invoice' =>    'null',
                'tgl_pengajuan' => $request->input('tgl_pengajuan'),
                'tgl_tempo' => $request->input('tgl_tempo'),
                'id_debitur' => $request->input('id_debitur'),
                'status_piutang' => "belum lunas"
            ];
            $result = PiutangModel::create(
                $payload
            );
            $payloadUpdate = [
                'no_invoice' => 'INV-'.substr(str_repeat(0, $length).$result->id, - $length).'/'.$this->numberToRomanRepresentation(date("m", strtotime($request->input('tgl_pengajuan')))).'/RSKP/'.date("Y", strtotime($request->input('tgl_pengajuan'))),
            ];
            $resultUpdate = PiutangModel::where('id', $result->id)
                        ->update($payloadUpdate);

            if($resultUpdate){
                return redirect('/piutang')->with('success', "Berhasil menambahkan data");
            }else{
                return redirect('/piutang')->with('failed', "Gagal menambahkan data, silahkan coba lagi!!");
            }
    }
    public function destroy($id){
        $result = PiutangModel::destroy($id);
        if($result){
            return redirect('/piutang')->with('success', "Berhasil menghapus data");
        }else{
            return redirect('/piutang')->with('failed', "Gagal menghapus data, silahkan coba lagi!!");
        }
    }
    public function update(Request $request){
        $payload = [
            'no_invoice' => $request->input('no_invoice'),
            'tgl_pengajuan' => $request->input('tgl_pengajuan'),
            'tgl_tempo' => $request->input('tgl_tempo'),
            'id_debitur' => $request->input('id_debitur'),
            'status_piutang' => "unpaid"
        ];
        $result = PiutangModel::where('id', $request->id)
                    ->update($payload);

        if($result){
            return redirect('/piutang')->with('success', "Berhasil mengubah piutang");
        }else{
            return redirect('/piutang')->with('failed', "Gagal mengubah piutang, silahkan coba lagi!!");
        }
    }

}
