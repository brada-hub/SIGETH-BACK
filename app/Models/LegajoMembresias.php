<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoMembresias extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_membresias';

    protected $fillable = [
        'empleado_id',
        'institucion',
        'calidad_participacion',
        'año_inicio',
        'año_fin',
        'archivo_path',
        'estado'
    ];

    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoMembresia()
    {
        return $this->belongsTo(TipoMembresia::class, 'tipo_membresia_id');
    }
}
