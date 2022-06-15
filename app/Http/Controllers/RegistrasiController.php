<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrasiController extends Controller
{
    public function index(){
        return view('registrasi.index', [
            'title' => 'Registrasi'
        ]);
    }

    public function store(Request $request){
        $checker = User::where('nip', $request->nip)->first();

        if($checker){
            $ValidatedData = $request->validate([
                'email'     => 'required|email:dns|unique:users',
                'nip'       => 'required|min:3|max:200',
                'no_tlp'    => 'required',
                'username'  => 'required|min:3|max:200|unique:users',
                'password'  => 'required|min:5|max:255'
            ]);
            $ValidatedData['password'] = bcrypt($ValidatedData['password']);

            $result = DB::table('users')->where('nip', $request->nip)
            ->update($ValidatedData);

            if($result){
                return redirect('/')->with('success', "Registrasi berhasil, silahkan login!");
            }else{
                return redirect('/registrasi');
            }
        }else{
            return redirect('/registrasi')->with('failed', "Maaf NIP tidak terdaftar!");
        }
    }
}
