<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoReconocimientoRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'titulo' => 'required|string|max:255',
            'institucion_otorgante' => 'required|string|max:255',
            'año' => 'required|string|size:4',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'titulo' => strtoupper($this->titulo),
            'institucion_otorgante' => strtoupper($this->institucion_otorgante),
        ]);
    }
}
