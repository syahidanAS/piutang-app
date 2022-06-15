<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalModel;
use Illuminate\Support\Facades\DB;
use PDO;

class JurnalController extends Controller
{
    public function index(){
        $flag = "before-search";
        $dataJurnal = JurnalModel::get();
       return view('jurnal.index', compact('flag','dataJurnal'));
    }
    public function afterSearch(Request $request){
        $flag = "after-search";
        $tahun = $request->tahun;
        $dataJurnal = JurnalModel::
        whereYear('created_at', $tahun)
        ->get();
       return view('jurnal.index', compact('flag','dataJurnal','tahun'));
    }
}
