<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSeguro extends Model
{
    protected $table = 'tipos_seguro';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
