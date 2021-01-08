<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    //
    protected $table = 'oficinas';
    protected $fillable = ['id','nombre'];
    public $timestamps = false;
}
