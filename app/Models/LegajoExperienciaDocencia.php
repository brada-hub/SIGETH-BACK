<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoExperienciaDocencia extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_experiencia_docencia';

    protected $fillable = [
        'empleado_id',
        'institucion',
        'ciudad',
        'pais_id',
        'facultad_unidad',
        'dedicacion',
        'materias',
        'fecha_inicio',
        'fecha_fin',
        'archivo_path',
        'estado'
    ];

    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }
}
