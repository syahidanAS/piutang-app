<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebiturModel extends Model
{
    use HasFactory;
    protected $table = 'debitur';
    protected $fillable = [
        'nm_debitur', 'alamat', 'email_deb', 'tlp_deb'
    ];
}
