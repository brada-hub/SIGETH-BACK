<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpleadoPosicion extends Model
{
    protected $table = 'empleado_posiciones';

    protected $fillable = [
        'empleado_id',
        'posicion_id',
        'orden',
        'activo',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function posicion(): BelongsTo
    {
        return $this->belongsTo(PosicionInstitucional::class, 'posicion_id');
    }
}
