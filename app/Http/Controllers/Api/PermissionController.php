<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use App\Models\Sistema;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permiso::all());
    }

    public function byApplication($applicationId)
    {
        return response()->json(Permiso::where('sistema_id', $applicationId)->get());
    }
}
