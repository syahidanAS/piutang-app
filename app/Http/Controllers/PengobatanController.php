<?php

namespace App\Http\Controllers;

use App\Models\PengobatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengobatanController extends Controller
{
    public function index(){
        $pengobatan = PengobatanModel::latest()->get();
        return view('pengobatan.index', compact(
            'pengobatan'
        ));
    }

    public function api(Request $request){
        $debiturs = PengobatanModel::where('nama_pelayanan','like',"%".$request->nama_pelayanan."%")
        ->orWhere('kd_layanan','like',"%".$request->kd_layanan."%")
        ->orWhere('unit_layanan','like',"%".$request->unit_layanan."%")
        ->latest()
        ->get();
        return $debiturs;
    }

    public function store(Request $request){
        $validator = PengobatanModel::where('kd_layanan', $request->kd_layanan)->get();
        if(count($validator)>0){
            return redirect('/jenis-pengobatan')->with('failed', "Gagal menambahkan data kerena kode layanan sudah digunakan!");

        }else{
            $ValidatedData = $request->validate([
                'kd_layanan'        => 'required|max:100',
                'nama_pelayanan'    => 'required|max:255',
                'unit_layanan'      => 'required',
                'harga'             => 'required'
            ]);
    
            $result = PengobatanModel::insert([
                $ValidatedData
            ]);
    
            if($result){
                return redirect('/jenis-pengobatan')->with('success', "Berhasil menambahkan jenis pengobatan");
            }else{
                return redirect('/jenis-pengobatan')->with('failed', "Gagal menambahkan jenis pengobatan, silahkan coba lagi!!");
            }
        }

    }

    public function destroy($id){
        $result = PengobatanModel::destroy($id);

        if($result){
            return redirect('/jenis-pengobatan')->with('success', "Berhasil menghapus data");
        }else{
            return redirect('/jenis-pengobatan')->with('failed', "Gagal menghapus data, silahkan coba lagi!!");
        }

    }

    public function update(Request $request){
        $ValidatedData = $request->validate([
            'kd_layanan'           => 'required|max:100',
            'nama_pelayanan'       => 'required|max:255',
            'unit_layanan'         => 'required',
            'harga'                => 'required'
        ]);

        $result = PengobatanModel::where('id', $request->id)
                    ->update($ValidatedData);

        if($result){
            return redirect('/jenis-pengobatan')->with('success', "Berhasil mengubah jenis pengobatan");
        }else{
            return redirect('/jenis-pengobatan')->with('failed', "Gagal mengubah jenis pengobatan, silahkan coba lagi!!");
        }
    }
}
