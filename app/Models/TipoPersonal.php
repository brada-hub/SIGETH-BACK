<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPersonal extends Model
{
    protected $table = 'tipos_personal';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'tipo_personal_id');
    }
}
