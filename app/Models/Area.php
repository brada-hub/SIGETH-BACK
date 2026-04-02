<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'sigla',
        'area_padre_id',
        'activo',
    ];
    protected $casts = ['activo' => 'integer'];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_padre_id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(Area::class, 'area_padre_id');
    }
}
