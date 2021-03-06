<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranModel extends Model
{
    use HasFactory;
    protected $table = 'pembayaran';
    protected $fillable = [
        'id_piutang',
        'total_tagihan',
        'total_pembayaran',

    ];
    protected $dates = ['tgl_tempo','tgl_pengajuan'];
    
    public function piutang(){
        return $this->belongsTo(PiutangModel::Class, 'id_piutang','id');
    }

    public function detailPembayaran(){
        return $this->hasMany(DetailPembayaran::Class, 'id_pembayaran','id');
    }
}
