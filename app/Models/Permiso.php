<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'modulo',
        'sistema_id',
    ];

    protected $appends = ['application_id', 'name'];

    public function getApplicationIdAttribute()
    {
        return $this->sistema_id;
    }

    public function getNameAttribute()
    {
        return $this->nombre;
    }

    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Sistema::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'rol_permisos')
            ->using(RolPermiso::class);
    }
}
