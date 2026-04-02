<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contratoId = $this->route('contrato') ? $this->route('contrato')->id : null;

        return [
            'empleado_id' => 'sometimes|required|exists:empleados,id',
            'cargo_id' => 'sometimes|required|exists:cargos,id',
            'tipo_contrato_id' => 'sometimes|required|exists:tipos_contrato,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'nro_contrato' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('contratos', 'nro_contrato')->ignore($contratoId),
            ],
            'fecha_inicio' => 'sometimes|required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'salario' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|required|in:Vigente,Finalizado,Rescindido',
            'observaciones' => 'nullable|string',
        ];
    }
}
