<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoEventosRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'nombre_evento' => 'required|string|max:255',
            'organizador' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'pais_id' => 'required|exists:paises,id',
            'año' => 'required|string|size:4',
            'tipo_participacion' => 'required|string|max:100', // Changed to string as per migration
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre_evento' => strtoupper($this->nombre_evento),
            'organizador' => strtoupper($this->organizador),
            'ciudad' => strtoupper($this->ciudad),
            'tipo_participacion' => strtoupper($this->tipo_participacion),
        ]);
    }
}
