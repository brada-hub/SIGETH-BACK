<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoFormacionAcademicaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'institucion' => 'required|string|max:255',
            'titulo_obtenido' => 'required|string|max:255',
            'año' => 'required|string|size:4',
            'nivel_academico_id' => 'required|exists:niveles_academicos,id',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'required|exists:paises,id',
            'fecha_diploma' => 'nullable|date',
            'fecha_titulo' => 'nullable|date',
            'archivo_titulo' => 'required|file|mimes:pdf|max:5120',
            'archivo_diploma' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'institucion' => strtoupper($this->institucion),
            'titulo_obtenido' => strtoupper($this->titulo_obtenido),
            'ciudad' => strtoupper($this->ciudad),
        ]);
    }
}
