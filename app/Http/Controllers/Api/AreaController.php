<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Area::where('activo', 1)->get());
    }
}
