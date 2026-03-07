<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'ci', 'nombres', 'apellido_paterno', 'apellido_materno',
        'email', 'password', 'phone', 'avatar',
        'sede_id', 'jurisdiccion', 'rol_id',
        'activo', 'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['nombre_completo', 'permisos', 'systems'];

    protected $with = ['rol', 'sede'];

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
        return $this->belongsToMany(Application::class, 'application_user', 'user_id', 'application_id')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'model_has_permissions', 'model_id', 'permission_id')
                    ->where('model_type', self::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public function getPermisosAttribute(): array
    {
        try {
            // Unir todos los permisos (del rol + directos)
            $directPerms = $this->permissions()->pluck('name');

            if ($this->rol_id) {
                $rolePerms = DB::connection($this->getConnectionName())
                    ->table('role_has_permissions')
                    ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                    ->where('role_id', $this->rol_id)
                    ->pluck('permissions.name');
                return $directPerms->merge($rolePerms)->unique()->values()->toArray();
            }

            return $directPerms->unique()->values()->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getSystemsAttribute(): array
    {
        return $this->applications()->pluck('nombre')->toArray();
    }
}
