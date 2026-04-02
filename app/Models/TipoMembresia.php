<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMembresia extends Model
{
    protected $table = 'tipos_membresia';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
