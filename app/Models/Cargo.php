<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = ['nombre', 'tipo_personal_id', 'descripcion', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    public function tipoPersonal()
    {
        return $this->belongsTo(TipoPersonal::class, 'tipo_personal_id');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
