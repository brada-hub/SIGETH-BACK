<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LegajoArchivo extends Model
{
    protected $table = 'legajo_archivos';

    protected $fillable = [
        'legajo_tabla',
        'legajo_id',
        'tipo_archivo',
        'archivo_path',
        'nombre_original',
        'mime_type',
        'tamano_bytes',
    ];

    /**
     * Scope para filtrar por origen de legajo.
     */
    public function scopeDeTabla(Builder $query, string $tabla, int $id): Builder
    {
        return $query->where('legajo_tabla', $tabla)
                     ->where('legajo_id', $id);
    }
}
