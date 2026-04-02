<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPlanilla extends Model
{
    protected $table = 'tipos_planilla';
    public $timestamps = false;
    protected $fillable = ['nombre', 'activo'];
    protected $casts = ['activo' => 'integer'];
}
