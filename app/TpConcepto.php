<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TpConcepto extends Model
{
    protected $table = 'tp_conceptos';
    protected $fillable = [
        'id', 
        'codigo',
        'nombre',
    ];
}
