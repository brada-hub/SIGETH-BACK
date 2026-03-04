<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $fillable = ['nombre', 'sigla', 'departamento', 'direccion', 'ciudad', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
