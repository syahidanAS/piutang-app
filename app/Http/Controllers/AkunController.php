<?php

namespace App\Http\Controllers;

use App\Models\AkunModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    public function index(){
        $akun = AkunModel::latest()->get();
        return view('akun.index',compact(
            'akun'
        ));
    }

    public function api(Request $request){
        $akun = AkunModel::where('nama_akun','like',"%".$request->nama_akun."%")
        ->latest()
        ->get();
        return $akun;
    }

    public function store(Request $request){
        $ValidatedData = $request->validate([
            'no_akun'    => 'required|max:100',
            'nama_akun'  => 'required|max:255'
        ]);

        $result = AkunModel::insert([
            $ValidatedData
        ]);

        if($result){
            return redirect('/akun')->with('success', "Berhasil menambahkan akun");
        }else{
            return redirect('/akun')->with('failed', "Gagal menambahkan akun, silahkan coba lagi!!");
        }
    }

    public function update(Request $request){
        $ValidatedData = $request->validate([
            'no_akun'    => 'required|max:100',
            'nama_akun'  => 'required|max:255'
        ]);

        $result = AkunModel::where('id', $request->id)
                    ->update($ValidatedData);

        if($result){
            return redirect('/akun')->with('success', "Berhasil mengubah akun");
        }else{
            return redirect('/akun')->with('failed', "Gagal mengubah akun, silahkan coba lagi!!");
        }
    }

    public function destroy($id){
        $result = AkunModel::destroy($id);

        if($result){
            return redirect('/akun')->with('success', "Berhasil menghapus data");
        }else{
            return redirect('/akun')->with('failed', "Gagal menghapus data, silahkan coba lagi!!");
        }

    }
}
