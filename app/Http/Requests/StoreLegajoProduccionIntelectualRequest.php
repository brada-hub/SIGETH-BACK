<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoProduccionIntelectualRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'titulo' => 'required|string|max:500',
            'referencia_bibliografica' => 'nullable|string',
            'año' => 'required|string|size:4',
            'pais_id' => 'required|exists:paises,id',
            'doi' => 'nullable|string|max:255',
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'titulo' => strtoupper($this->titulo),
        ]);
    }
}
