<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunModel extends Model
{
    use HasFactory;
    protected $table = 'akun';
    protected $fillable = [
        'no_akun', 'nama_akun'
    ];
}
