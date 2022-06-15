<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangModel extends Model
{
    use HasFactory;
    protected $table = 'piutang';
    protected $fillable = [
        'no_invoice', 'tgl_pengajuan', 'tgl_tempo', 'id_debitur','id_layanan','tagihan','status_piutang'
    ];
    protected $dates = ['tgl_tempo','tgl_pengajuan'];
}
