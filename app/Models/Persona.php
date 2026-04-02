<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persona extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'primer_apellido',
        'segundo_apellido',
        'nombres',
        'ci',
        'ci_expedicion',
        'fecha_nacimiento',
        'genero_id',
        'estado_civil_id',
        'nacionalidad_id',
        'departamento_residencia',
        'ciudad_residencia',
        'direccion',
        'calle_avenida',
        'numero_domicilio',
        'correo_personal',
        'celular_personal',
        'foto',
        'resumen',
    ];

    /**
     * Accessor para el nombre completo.
     * Formato: AP. PATERNO AP. MATERNO, NOMBRES
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->primer_apellido . ' ' . $this->segundo_apellido . ', ' . $this->nombres;
    }

    /* Relaciones */

    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'persona_id');
    }

    public function herederos(): HasMany
    {
        return $this->hasMany(EmpleadoHeredero::class, 'persona_id');
    }

    public function genero(): BelongsTo
    {
        return $this->belongsTo(Genero::class);
    }

    public function estadoCivil(): BelongsTo
    {
        return $this->belongsTo(EstadoCivil::class);
    }

    /**
     * User account linked through empleado.
     */
    public function user()
    {
        // One Persona has one Empleado, which has one User.
        return $this->hasOneThrough(
            User::class,
            Empleado::class,
            'persona_id',   // Foreign key on Empleado table
            'empleado_id',  // Foreign key on User table
            'id',           // Local key on Persona table
            'id'            // Local key on Empleado table
        );
    }

    public function ciExpedicionDepartamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'ci_expedicion', 'id');
    }
}
