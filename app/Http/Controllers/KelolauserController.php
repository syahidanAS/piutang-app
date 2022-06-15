<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolauserController extends Controller
{
    public function index(){
        $users = User::latest()->get();
        return view('kelolaUser.index', compact('users'));
    }
    public function store(Request $request){
        $checker = User::where('nip', $request->nip)->first();

        if($checker){
            return redirect('/users')->with('failed', "Maaf, NIP sudah digunakan!");
        }else{
            $payload = [
                "nama" => $request->nama,
                "nip"  => $request->nip,
                "role" => $request->role
            ];
            $result = User::insert([
                $payload
            ]);

            if ($result) {
                return redirect('/users')->with('success', "Berhasil menambahkan user baru");
            } else {
                return redirect('/users')->with('failed', "Gagal menambahkan user baru, silahkan coba lagi!!");
            }
        }

    }

    public function update(Request $request){
        $payload = [
            "nama"      => $request->input('nama'),
            "email"     => $request->input('email'),
            "nip"       => $request->input('nip'),
            "no_tlp"    => $request->input('no_tlp')
        ];
        $result = User::where('id', $request->id)
        ->update($payload);

        if ($result) {
            return redirect('/users')->with('success', "Berhasil mengubah user barudata");
        } else {
            return redirect('/users')->with('failed', "Gagal mengubah data, silahkan coba lagi!");
        }
    }

    public function destroy($id){
        $result = User::destroy($id);

        if ($result) {
            return redirect('/users')->with('success', "Berhasil menghapus data");
        } else {
            return redirect('/users')->with('failed', "Gagal menghapus data, silahkan coba lagi!");
        }
    }
}
