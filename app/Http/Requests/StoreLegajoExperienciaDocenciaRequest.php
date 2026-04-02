<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoExperienciaDocenciaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'institucion' => 'required|string|max:255',
            'facultad_unidad' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'required|exists:paises,id',
            'materias' => 'required|string',
            'dedicacion' => 'nullable|string|max:100',
            'fecha_inicio' => 'required|string|max:20',
            'fecha_fin' => 'nullable|string|max:20',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }
}
