<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoBachilleratoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'titulo_obtenido' => 'required|string|max:255',
            'año' => 'required|string|size:4',
            'institucion' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'pais_id' => 'required|exists:paises,id',
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'titulo_obtenido' => strtoupper($this->titulo_obtenido),
            'institucion' => strtoupper($this->institucion),
            'ciudad' => strtoupper($this->ciudad),
        ]);
    }
}
