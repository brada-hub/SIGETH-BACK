<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sistema;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return response()->json(Sistema::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:sistemas,nombre',
            'url' => 'required',
        ]);

        $app = Sistema::create($request->only([
            'nombre', 'slug', 'url', 'activo'
        ]));

        return response()->json($app, 201);
    }

    public function update(Request $request, $id)
    {
        $app = Sistema::findOrFail($id);
        $app->update($request->only([
            'nombre', 'slug', 'url', 'activo'
        ]));
        return response()->json($app);
    }

    public function destroy($id)
    {
        Sistema::findOrFail($id)->delete();
        return response()->json(['message' => 'Sistema eliminado']);
    }
}
