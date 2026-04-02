<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPosgrado extends Model
{
    protected $table = 'tipos_posgrado';
    public $timestamps = false;
    protected $fillable = ['nombre', 'orden'];
}
