<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'persona_id',
        'username',
        'password',
        'ci',
        'nombres',
        'apellidos',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'phone',
        'sede_id',
        'jurisdiccion',
        'rol_id',
        'activo',
        'must_change_password',
        'ultimo_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['permisos'];

    public function getPermisosAttribute(): array
    {
        $permisos = [];
        if ($this->relationLoaded('roles')) {
            foreach ($this->roles as $rol) {
                if ($rol->relationLoaded('permisos')) {
                    foreach ($rol->permisos as $permiso) {
                        $permisos[] = $permiso->nombre;
                    }
                }
            }
        }
        return array_values(array_unique($permisos));
    }

    protected $casts = [
        'ultimo_login' => 'datetime',
        'jurisdiccion' => 'array',
        'activo' => 'boolean',
    ];

    /* Relaciones */

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * @deprecated Usar persona() directamente
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRol::class);
    }

    /**
     * Roles asignados al usuario a través de la tabla intermedia.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'user_roles')
            ->withPivot(['id', 'sistema_id', 'activo'])
            ->withTimestamps();
    }

    /**
     * Alias para compatibilidad con el frontend que espera un solo rol.
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Sistemas (aplicaciones) a los que tiene acceso el usuario.
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Sistema::class, 'user_roles', 'user_id', 'sistema_id')
            ->distinct()
            ->withPivot(['activo'])
            ->withTimestamps();
    }
}
