<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalModel extends Model
{
    use HasFactory;
    protected $table = 'table_jurnal';
    protected $fillable = [
        'id_piutang', 'no_jurnal', 'keterangan','kode_perkiraan','nama_perkiraan','nominal'
    ];
    protected $dates = ['created_at'];
}
