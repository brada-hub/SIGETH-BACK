<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    protected $table = 'tipos_contrato';
    public $timestamps = false;
    protected $fillable = ['nombre', 'activo'];
    protected $casts = ['activo' => 'integer'];
}
