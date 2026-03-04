<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        return response()->json(Sede::orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:sedes,nombre',
            'sigla' => 'required|max:10',
        ]);

        $sede = Sede::create($request->only([
            'nombre', 'sigla', 'departamento', 'direccion', 'ciudad', 'activo'
        ]));

        return response()->json($sede, 201);
    }

    public function update(Request $request, $id)
    {
        $sede = Sede::findOrFail($id);
        $sede->update($request->only([
            'nombre', 'sigla', 'departamento', 'direccion', 'ciudad', 'activo'
        ]));
        return response()->json($sede);
    }

    public function destroy($id)
    {
        Sede::findOrFail($id)->delete();
        return response()->json(['message' => 'Sede eliminada']);
    }
}
