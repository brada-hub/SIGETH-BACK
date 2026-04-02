<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLegajoIdiomasRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'idioma_id' => 'required|exists:idiomas,id',
            'lectura' => 'required|string|max:50',
            'escritura' => 'required|string|max:50',
            'conversacion' => 'required|string|max:50',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'lectura' => strtoupper($this->lectura),
            'escritura' => strtoupper($this->escritura),
            'conversacion' => strtoupper($this->conversacion),
        ]);
    }
}
