<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PiutangModel;
use App\Models\PembayaranModel;

class DashboardController extends Controller
{
    public function index(){
        $piutang = PiutangModel::selectRaw('SUM(total_piutang) AS total_piutang')->first();
        $pembayaran = PembayaranModel::selectRaw('SUM(total_pembayaran) AS total_pembayaran')->first(); 
        $totalPiutang = (int)$piutang['total_piutang'];
        $totalPembayaran = (int)$pembayaran['total_pembayaran'];
        $sisaPiutang = $totalPiutang - $totalPembayaran;
        return view('dashboard.index', compact(
            'sisaPiutang',
            'totalPiutang',
            'totalPembayaran',
        ));
    }
}
