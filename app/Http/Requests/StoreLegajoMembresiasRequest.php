<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoMembresiasRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'institucion' => 'required|string|max:255',
            'calidad_participacion' => 'required|string|max:150',
            'año_inicio' => 'required|string|size:4',
            'año_fin' => 'nullable|string|size:4',
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'institucion' => strtoupper($this->institucion),
            'calidad_participacion' => strtoupper($this->calidad_participacion),
        ]);
    }
}
