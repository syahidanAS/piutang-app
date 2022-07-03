<?php

namespace App\Http\Controllers;

use App\Models\DebiturModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebiturController extends Controller
{
    public function index()
    {
        $debiturs = DebiturModel::get();
        return view('debitur.index', compact(
            'debiturs'
        ));
    }
    public function api(Request $request)
    {
        $debiturs = DebiturModel::where('nm_debitur', 'like', "%" . $request->nm_debitur . "%")
            ->get();
        return $debiturs;
    }
    public function store(Request $request)
    {
        $kode_debitur;
        $checker = DebiturModel::get();



        $payload = [
            'nm_debitur' => $request->nm_debitur,
            'alamat' => $request->alamat,
            'email_deb' => $request->email_deb,
            'tlp_deb' => $request->tlp_deb,
        ];

        $result = DebiturModel::create($payload);


        if($checker){
            $kode_debitur = sprintf('D'.'%03d', $result->id);
        }else{
           $kode_debitur = "D001";
        }

        $resultUpdatedId = DebiturModel::where('id', $result->id)
        ->update(['kode_debitur'=> $kode_debitur]);



        if ($result) {
            return redirect('/debitur')->with('success', "Berhasil menambahkan debitur");
        } else {
            return redirect('/debitur')->with('failed', "Gagal menambahkan debitur, silahkan coba lagi!!");
        }
    }

    public function destroy($id)
    {
        $result = DebiturModel::destroy($id);

        if ($result) {
            return redirect('/debitur')->with('success', "Berhasil menghapus data");
        } else {
            return redirect('/debitur')->with('failed', "Gagal menghapus data, silahkan coba lagi!!");
        }
    }
    public function update(Request $request)
    {
        $ValidatedData = $request->validate([
            'nm_debitur'    => 'required|max:100',
            'alamat'        => 'required|max:255',
            'email_deb'     => 'required',
            'tlp_deb'       => 'required'
        ]);

        $result = DebiturModel::where('id', $request->id)
            ->update($ValidatedData);

        if ($result) {
            return redirect('/debitur')->with('success', "Berhasil mengubah debitur");
        } else {
            return redirect('/debitur')->with('failed', "Gagal mengubah debitur, silahkan coba lagi!!");
        }
    }
}
