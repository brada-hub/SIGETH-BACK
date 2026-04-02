<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoPosgrado extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_posgrado';

    protected $fillable = [
        'empleado_id',
        'tipo_postgrado',
        'titulo_obtenido',
        'carga_horaria',
        'año_titulacion',
        'institucion',
        'ciudad',
        'pais_id',
        'area_estudios',
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

    public function tipoPosgrado()
    {
        return $this->belongsTo(TipoPosgrado::class, 'tipo_posgrado_id');
    }
}
