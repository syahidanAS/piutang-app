<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengobatanModel extends Model
{
    use HasFactory;
    protected $table = 'jenis_pengobatan';
    protected $fillable = [
        'no_akun', 'nama_akun'
    ];
}
