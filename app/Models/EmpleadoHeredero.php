<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpleadoHeredero extends Model
{
    protected $table = 'empleado_herederos';

    protected $fillable = [
        'empleado_id',
        'persona_id',
        'parentesco_id',
        'porcentaje',
        'orden',
        'activo',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class);
    }
}
