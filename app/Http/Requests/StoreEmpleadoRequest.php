<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpleadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Datos de Persona
            'nombres' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'ci' => 'required|string|max:20|unique:personas,ci',
            'ci_expedicion' => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'genero_id' => 'nullable|exists:generos,id',
            'estado_civil_id' => 'nullable|exists:estados_civiles,id',
            'nacionalidad_id' => 'nullable|exists:paises,id',
            'correo_personal' => 'nullable|email|max:255',
            'celular_personal' => 'nullable|string|max:20',

            // Datos de Empleado
            'correo_institucional' => 'nullable|email|max:255|unique:empleados,correo_institucional',
            'celular_institucional' => 'nullable|string|max:20',
            'sede_id' => 'nullable|exists:sedes,id',
            'area_trabajo_id' => 'nullable|exists:areas,id',
            'area_dependencia_id' => 'nullable|exists:areas,id',
            'tipo_seguro_id' => 'nullable|exists:tipos_seguro,id',
            'tipo_planilla_id' => 'nullable|exists:tipos_planilla,id',
            'fecha_ingreso' => 'nullable|date',
            'num_seguro_cns' => 'nullable|string|max:50',
            'num_seguro_afp' => 'nullable|string|max:50',

            // Datos de Contrato (Opcional en creación)
            'cargo_id'         => 'nullable|exists:cargos,id',
            'tipo_contrato_id' => 'nullable|exists:tipos_contrato,id',
            'nro_contrato'     => 'nullable|string|max:100',
            'fecha_inicio'     => 'nullable|date',
            'fecha_fin'        => 'nullable|date',
            'salario'          => 'nullable|numeric|min:0',
            'bono_frontera'    => 'nullable|numeric|min:0',
        ];
    }
}
