<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLegajoBachilleratoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'titulo_obtenido' => 'sometimes|required|string|max:255',
            'año' => 'sometimes|required|string|size:4',
            'institucion' => 'sometimes|required|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'pais_id' => 'sometimes|required|exists:paises,id',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'titulo_obtenido' => $this->titulo_obtenido ? strtoupper($this->titulo_obtenido) : null,
            'institucion' => $this->institucion ? strtoupper($this->institucion) : null,
            'ciudad' => $this->ciudad ? strtoupper($this->ciudad) : null,
        ]);
        // Filter out nulls
        $this->replace(array_filter($this->all(), fn($val) => !is_null($val)));
    }
}
