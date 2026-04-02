<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoReconocimiento extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_reconocimiento';

    protected $fillable = [
        'empleado_id',
        'titulo',
        'institucion_otorgante',
        'año',
        'descripcion',
        'archivo_path',
        'estado'
    ];

    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoReconocimiento()
    {
        return $this->belongsTo(TipoReconocimiento::class, 'tipo_reconocimiento_id');
    }
}
