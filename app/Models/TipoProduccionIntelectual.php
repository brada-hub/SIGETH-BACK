<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProduccionIntelectual extends Model
{
    protected $table = 'tipos_produccion_intelectual';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
