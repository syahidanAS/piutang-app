<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalModel;
use Illuminate\Support\Facades\DB;
use PDO;

class JurnalController extends Controller
{
    public function index(){
        $from = "";
        $to = "";
        $flag = "before-search";
        $dataJurnal = JurnalModel::get();
       return view('jurnal.index', compact('flag','dataJurnal','from','to'));
    }
    public function afterSearch(Request $request){
        $flag = "after-search";
        $from = $request->from;
        $to = $request->to;
        $periode = "Periode " . $from . "-" . $to;

        $dataJurnal = JurnalModel::
        whereBetween('tanggal', [$from,$to])
        ->get();
        return view('jurnal.index', compact('flag','dataJurnal','periode','from','to'));
    }
}
