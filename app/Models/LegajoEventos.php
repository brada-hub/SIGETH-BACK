<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoEventos extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_eventos';

    protected $fillable = [
        'empleado_id',
        'nombre_evento',
        'organizador',
        'ciudad',
        'pais_id',
        'año',
        'tipo_participacion',
        'archivo_path',
        'estado'
    ];

    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'tipo_evento_id');
    }

    public function tipoParticipacion()
    {
        return $this->belongsTo(TipoParticipacionEvento::class, 'tipo_participacion_id');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }
}
