<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RolPermiso extends Pivot
{
    protected $table = 'rol_permisos';
    public $timestamps = false; // El DBML no especifica timestamps para rol_permisos
}
