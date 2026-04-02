<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'empleado_id',
        'cargo_id',
        'tipo_contrato_id',
        'sede_id',
        'nro_contrato',
        'fecha_inicio',
        'fecha_fin',
        'salario',
        'bono_frontera',
        'estado',
        'observaciones',
    ];

    /**
     * Accessor para el total ganado (Salario + Bono Frontera)
     */
    public function getTotalGanadoAttribute(): float
    {
        return (float) ($this->salario ?? 0) + (float) ($this->bono_frontera ?? 0);
    }

    /**
     * Accessor para verificar vigencia.
     */
    public function getEstaVigenteAttribute(): bool
    {
        return $this->estado === 'Vigente';
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function tipoContrato(): BelongsTo
    {
        return $this->belongsTo(TipoContrato::class, 'tipo_contrato_id');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function persona()
    {
        return $this->hasOneThrough(
            Persona::class,
            Empleado::class,
            'id', // Foreign key on Empleado table
            'id', // Foreign key on Persona table
            'empleado_id', // Local key on Contrato table
            'persona_id' // Local key on Empleado table
        );
    }
}
