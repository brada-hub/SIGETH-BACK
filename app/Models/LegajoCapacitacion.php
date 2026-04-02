<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoCapacitacion extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_capacitacion';

    protected $fillable = [
        'empleado_id',
        'nombre_evento',
        'institucion_organizadora',
        'carga_horaria',
        'año',
        'tipo_certificado',
        'archivo_path',
        'estado'
    ];

    protected $casts = [
        'aprobado' => 'boolean',
        'carga_horaria' => 'integer',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
}
