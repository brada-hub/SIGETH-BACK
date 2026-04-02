<?php

namespace App\Models;

use App\Traits\TieneValidacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoIdiomas extends Model
{
    use TieneValidacion;

    protected $table = 'legajo_idiomas';

    protected $fillable = [
        'empleado_id',
        'idioma_id',
        'lectura',
        'escritura',
        'conversacion',
        'archivo_path',
        'estado'
    ];

    protected $casts = [];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function idiomaCatalogo(): BelongsTo
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }

    public function nivelHabla()
    {
        return $this->belongsTo(NivelIdioma::class, 'nivel_habla_id');
    }

    public function nivelLectura()
    {
        return $this->belongsTo(NivelIdioma::class, 'nivel_lectura_id');
    }

    public function nivelEscritura()
    {
        return $this->belongsTo(NivelIdioma::class, 'nivel_escritura_id');
    }
}
