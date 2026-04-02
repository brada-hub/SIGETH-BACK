<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLegajoExperienciaProfesionalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'empresa_institucion' => 'sometimes|required|string|max:255',
            'cargo' => 'sometimes|required|string|max:255',
            'actividades' => 'nullable|string',
            'dedicacion' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'sometimes|required|exists:paises,id',
            'fecha_inicio' => 'sometimes|required|string|max:20',
            'fecha_fin' => 'nullable|string|max:20',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }
}
