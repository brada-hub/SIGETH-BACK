<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoExperienciaProfesional extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_experiencia_profesional';

    protected $fillable = [
        'empleado_id',
        'cargo',
        'empresa_institucion',
        'ciudad',
        'pais_id',
        'dedicacion',
        'fecha_inicio',
        'fecha_fin',
        'actividades',
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
