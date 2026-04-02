<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $fillable = [
        'nombre',
        'sigla',
        'departamento_id',
        'departamento',
        'ciudad',
        'activo',
    ];
    protected $casts = ['activo' => 'integer'];

    public function departamento_rel()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
