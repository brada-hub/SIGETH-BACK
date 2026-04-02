<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoFormacionAcademica extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_formacion_academica';

    protected $fillable = [
        'empleado_id',
        'institucion',
        'titulo_obtenido',
        'año',
        'nivel_academico_id',
        'ciudad',
        'pais_id',
        'fecha_diploma',
        'fecha_titulo',
        'archivo_diploma_path',
        'archivo_titulo_path',
        'estado',
        'observacion',
        'validado_por',
        'validado_at',
    ];

    protected $casts = [
        'fecha_diploma' => 'date',
        'fecha_titulo' => 'date',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function nivelAcademico(): BelongsTo
    {
        return $this->belongsTo(NivelAcademico::class, 'nivel_academico_id');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(NivelAcademico::class, 'nivel_academico_id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }
}
