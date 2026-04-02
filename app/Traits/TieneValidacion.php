<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TieneValidacion
{
    /**
     * Boot the trait to handle automatic fillable/casts if needed, 
     * although usually we define them in the model for clarity.
     */
    
    public function initializeTieneValidacion()
    {
        $this->fillable = array_merge($this->fillable, [
            'estado',
            'observacion',
            'validado_por',
            'validado_at',
        ]);

        $this->casts = array_merge($this->casts, [
            'validado_at' => 'datetime',
            'estado' => 'string',
        ]);
    }

    /**
     * Scopes para filtrar por estado
     */
    public function scopePendientes(Builder $query): Builder
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeValidados(Builder $query): Builder
    {
        return $query->where('estado', 'validado');
    }

    public function scopeObservados(Builder $query): Builder
    {
        return $query->where('estado', 'observado');
    }

    public function scopeRechazados(Builder $query): Builder
    {
        return $query->where('estado', 'rechazado');
    }

    /**
     * Retorna si el registro está validado
     */
    public function estaValidado(): bool
    {
        return $this->estado === 'validado';
    }

    /**
     * Relación con el usuario que validó
     */
    public function validador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por');
    }
}
