<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLegajoFormacionAcademicaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'institucion' => 'sometimes|required|string|max:255',
            'titulo_obtenido' => 'sometimes|required|string|max:255',
            'año' => 'sometimes|required|string|size:4',
            'nivel_academico_id' => 'sometimes|required|exists:niveles_academicos,id',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'sometimes|required|exists:paises,id',
            'fecha_diploma' => 'nullable|date',
            'fecha_titulo' => 'nullable|date',
            'archivo_titulo' => 'nullable|file|mimes:pdf|max:5120',
            'archivo_diploma' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'institucion' => $this->institucion ? strtoupper($this->institucion) : null,
            'titulo_obtenido' => $this->titulo_obtenido ? strtoupper($this->titulo_obtenido) : null,
            'ciudad' => $this->ciudad ? strtoupper($this->ciudad) : null,
        ]);
        // Filter out nulls
        $this->replace(array_filter($this->all(), fn($val) => !is_null($val)));
    }
}
