<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaranModel extends Model
{
    use HasFactory;
    protected $table = 'detail_pembayaran';
    protected $fillable = [
        'no_pembayaran',
        'id_pembayaran',
        'tgl_pembayaran',
        'total_pembayaran',
        'sisa_tagihan',
        'status',
    ];
    protected $dates = ['tgl_pembayaran', 'tgl_pengajuan'];
}
