<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'ci', 'nombres', 'apellidos', 'apellido_paterno', 'apellido_materno',
        'email', 'password', 'phone', 'avatar',
        'sede_id', 'jurisdiccion', 'rol_id',
        'activo', 'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['nombre_completo', 'permisos'];

    protected $with = ['rol'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'must_change_password' => 'boolean',
            'jurisdiccion' => 'array',
        ];
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class)
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    public function getNombreCompletoAttribute(): string
    {
        $nombre = $this->nombres;
        if ($this->apellido_paterno) {
            $nombre .= ' ' . $this->apellido_paterno;
        }
        if ($this->apellido_materno) {
            $nombre .= ' ' . $this->apellido_materno;
        }
        if (!$this->apellido_paterno && $this->apellidos) {
            $nombre .= ' ' . $this->apellidos;
        }
        return $nombre;
    }

    public function getPermisosAttribute(): array
    {
        try {
            // Solo permisos directos del usuario (el rol es solo una etiqueta)
            return \DB::table('model_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->where('model_has_permissions.model_id', $this->id)
                ->where('model_has_permissions.model_type', 'App\\Models\\User')
                ->pluck('permissions.name')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
