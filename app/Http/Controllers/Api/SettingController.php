<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Obtener el estado del link de Registro Directo (Público)
     */
    public function getRegistroDirectoStatus()
    {
        $setting = Setting::where('key', 'registro_directo_enabled')->first();
        return response()->json([
            'enabled' => $setting && $setting->value === 'true'
        ]);
    }

    /**
     * Cambiar el estado del link de Registro Directo (Solo Admins)
     */
    public function toggleRegistroDirectoStatus(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean'
        ]);

        $setting = Setting::firstOrCreate(
            ['key' => 'registro_directo_enabled'],
            ['value' => 'true']
        );

        $setting->value = $request->enabled ? 'true' : 'false';
        $setting->save();

        return response()->json([
            'success' => true,
            'message' => $request->enabled ? 'El enlace de registro público ha sido HABILITADO.' : 'El enlace de registro público ha sido DESHABILITADO.',
            'enabled' => $request->enabled
        ]);
    }
}
