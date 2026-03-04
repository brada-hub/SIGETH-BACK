<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['nombre', 'url', 'icono', 'color', 'descripcion', 'activo'];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }
}
