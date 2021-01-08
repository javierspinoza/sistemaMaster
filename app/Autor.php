<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autores';
    protected $fillable = [
        'id', 
        'nombre',
        'apellidos',
        'email',
        'condicion',
        'id_pais',
    ];
}
