<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoBachillerato extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_bachillerato';

    protected $fillable = [
        'empleado_id',
        'titulo_obtenido',
        'año',
        'institucion',
        'ciudad',
        'pais_id',
        'archivo_path',
        'estado'
    ];
    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoColegio(): BelongsTo
    {
        return $this->belongsTo(TipoColegio::class, 'tipo_colegio_id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }
}
