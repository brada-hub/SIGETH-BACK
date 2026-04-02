<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosicionInstitucional extends Model
{
    protected $table = 'posiciones_institucionales';
    public $timestamps = false;
    protected $fillable = ['nombre', 'activo'];
    protected $casts = ['activo' => 'integer'];
}
