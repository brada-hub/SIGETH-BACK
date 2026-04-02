<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLegajoExperienciaDocenciaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'institucion' => 'sometimes|required|string|max:255',
            'facultad_unidad' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'sometimes|required|exists:paises,id',
            'materias' => 'sometimes|required|string',
            'dedicacion' => 'nullable|string|max:100',
            'fecha_inicio' => 'sometimes|required|string|max:20',
            'fecha_fin' => 'nullable|string|max:20',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }
}
