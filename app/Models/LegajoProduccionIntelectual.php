<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoProduccionIntelectual extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_produccion_intelectual';

    protected $fillable = [
        'empleado_id',
        'titulo',
        'referencia_bibliografica',
        'año',
        'pais_id',
        'doi',
        'archivo_path',
        'estado'
    ];

    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function tipoProduccion()
    {
        return $this->belongsTo(TipoProduccion::class, 'tipo_produccion_id');
    }
}
