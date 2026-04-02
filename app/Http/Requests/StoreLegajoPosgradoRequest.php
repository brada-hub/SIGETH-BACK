<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoPosgradoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tipo_postgrado' => 'required|string|max:100',
            'titulo_obtenido' => 'required|string|max:255',
            'carga_horaria' => 'nullable|string|max:50',
            'año_titulacion' => 'required|string|size:4',
            'institucion' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:150',
            'pais_id' => 'required|exists:paises,id',
            'area_estudios' => 'nullable|string|max:255',
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_postgrado' => strtoupper($this->tipo_postgrado),
            'titulo_obtenido' => strtoupper($this->titulo_obtenido),
            'institucion' => strtoupper($this->institucion),
            'ciudad' => strtoupper($this->ciudad),
            'area_estudios' => strtoupper($this->area_estudios),
        ]);
    }
}
