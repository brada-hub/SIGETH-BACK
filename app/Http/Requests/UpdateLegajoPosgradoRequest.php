<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLegajoPosgradoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tipo_postgrado' => 'sometimes|required|string|max:100',
            'titulo_obtenido' => 'sometimes|required|string|max:255',
            'carga_horaria' => 'sometimes|nullable|string|max:50',
            'año_titulacion' => 'sometimes|required|string|size:4',
            'institucion' => 'sometimes|required|string|max:255',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'sometimes|required|exists:paises,id',
            'area_estudios' => 'sometimes|nullable|string|max:255',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_postgrado' => $this->tipo_postgrado ? strtoupper($this->tipo_postgrado) : null,
            'titulo_obtenido' => $this->titulo_obtenido ? strtoupper($this->titulo_obtenido) : null,
            'institucion' => $this->institucion ? strtoupper($this->institucion) : null,
            'ciudad' => $this->ciudad ? strtoupper($this->ciudad) : null,
            'area_estudios' => $this->area_estudios ? strtoupper($this->area_estudios) : null,
        ]);
        // Filter out nulls
        $this->replace(array_filter($this->all(), fn($val) => !is_null($val)));
    }
}
