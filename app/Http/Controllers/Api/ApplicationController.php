<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return response()->json(Application::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:applications,nombre',
            'url' => 'required',
        ]);

        $app = Application::create($request->only([
            'nombre', 'url', 'icono', 'color', 'descripcion', 'activo'
        ]));

        return response()->json($app, 201);
    }

    public function update(Request $request, $id)
    {
        $app = Application::findOrFail($id);
        $app->update($request->only([
            'nombre', 'url', 'icono', 'color', 'descripcion', 'activo'
        ]));
        return response()->json($app);
    }

    public function destroy($id)
    {
        Application::findOrFail($id)->delete();
        return response()->json(['message' => 'Aplicación eliminada']);
    }
}
