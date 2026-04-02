<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoCapacitacionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'nombre_evento' => 'required|string|max:255',
            'institucion_organizadora' => 'required|string|max:255',
            'carga_horaria' => 'required|string|max:50',
            'año' => 'required|string|size:4',
            'tipo_certificado' => 'required|in:APROBACIÓN,ASISTENCIA,Aprobación,Asistencia',
            'archivo' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre_evento' => strtoupper($this->nombre_evento),
            'institucion_organizadora' => strtoupper($this->institucion_organizadora),
        ]);
    }
}
