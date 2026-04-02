<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empleado extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'persona_id',
        'correo_institucional',
        'celular_institucional',
        'telefono_trabajo',
        'celular_corporativo',
        'sede_id',
        'area_trabajo_id',
        'area_dependencia_id',
        'num_seguro_cns',
        'num_seguro_afp',
        'tipo_seguro_id',
        'tipo_planilla_id',
        'fecha_ingreso',
        'activo',
    ];

    /* Relaciones Base */

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function posiciones(): HasMany
    {
        return $this->hasMany(EmpleadoPosicion::class);
    }

    public function herederos(): HasMany
    {
        return $this->hasMany(EmpleadoHeredero::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function areaTrabajo(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_trabajo_id');
    }

    public function areaDependencia(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_dependencia_id');
    }

    public function tipoSeguro(): BelongsTo
    {
        return $this->belongsTo(TipoSeguro::class, 'tipo_seguro_id');
    }

    public function tipoPlanilla(): BelongsTo
    {
        return $this->belongsTo(TipoPlanilla::class, 'tipo_planilla_id');
    }

    /* Legajo (digital file) */

    public function bachiller(): HasOne
    {
        return $this->hasOne(LegajoBachillerato::class);
    }

    public function formaciones(): HasMany
    {
        return $this->hasMany(LegajoFormacionAcademica::class);
    }

    public function posgrados(): HasMany
    {
        return $this->hasMany(LegajoPosgrado::class);
    }

    public function docencias(): HasMany
    {
        return $this->hasMany(LegajoExperienciaDocencia::class);
    }

    public function experiencias(): HasMany
    {
        return $this->hasMany(LegajoExperienciaProfesional::class);
    }

    public function capacitaciones(): HasMany
    {
        return $this->hasMany(LegajoCapacitacion::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(LegajoEventos::class);
    }

    public function reconocimientos(): HasMany
    {
        return $this->hasMany(LegajoReconocimiento::class);
    }

    public function producciones(): HasMany
    {
        return $this->hasMany(LegajoProduccionIntelectual::class);
    }

    public function membresias(): HasMany
    {
        return $this->hasMany(LegajoMembresias::class);
    }

    public function idiomas(): HasMany
    {
        return $this->hasMany(LegajoIdiomas::class);
    }

    /* Métodos de Negocio */

    /**
     * Retorna el número de hijos basado en herederos con parentesco 'Hijo/a'.
     */
    public function nroHijos(): int
    {
        return $this->herederos()
            ->whereHas('parentesco', fn($q) => $q->where('nombre', 'Hijo/a'))
            ->count();
    }
}
