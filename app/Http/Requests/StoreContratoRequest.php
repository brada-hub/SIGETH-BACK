<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empleado_id' => 'required|exists:empleados,id',
            'cargo_id' => 'required|exists:cargos,id',
            'tipo_contrato_id' => 'required|exists:tipos_contrato,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'nro_contrato' => 'nullable|string|max:100|unique:contratos,nro_contrato',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'salario' => 'nullable|numeric|min:0',
            'estado' => 'required|in:Vigente,Finalizado,Rescindido',
            'observaciones' => 'nullable|string',
        ];
    }
}
