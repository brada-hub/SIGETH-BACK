<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sistema extends Model
{
    protected $table = 'sistemas';

    protected $fillable = [
        'nombre',
        'slug',
        'url',
        'activo',
    ];

    public function roles(): HasMany
    {
        return $this->hasMany(Rol::class);
    }

    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class);
    }
}
