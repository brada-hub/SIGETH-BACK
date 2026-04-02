<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LegajoCompletoController;
use App\Http\Controllers\LegajoBachilleratoController;
use App\Http\Controllers\LegajoFormacionAcademicaController;
use App\Http\Controllers\LegajoPosgradoController;
use App\Http\Controllers\LegajoExperienciaDocenciaController;
use App\Http\Controllers\LegajoExperienciaProfesionalController;
use App\Http\Controllers\LegajoCapacitacionController;
use App\Http\Controllers\LegajoEventosController;
use App\Http\Controllers\LegajoReconocimientoController;
use App\Http\Controllers\LegajoProduccionIntelectualController;
use App\Http\Controllers\LegajoMembresiasController;
use App\Http\Controllers\LegajoIdiomasController;
use App\Http\Controllers\LegajoArchivoController;
use App\Http\Controllers\LegajoRRHHController;
use App\Http\Controllers\EmployeeAccessController;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\CvPdfController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\SedeController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\DocumentoController;
use App\Http\Controllers\Api\AreaController;

// ─── Autenticación pública ────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ─── Registro Directo (Público oculto) ────────────────────────────────────────
Route::get('/settings/registro-directo', [SettingController::class, 'getRegistroDirectoStatus']);
Route::post('/personas/verificar-seguridad', [PersonaController::class, 'verificarSeguridad']); // CI + Fecha Nacimiento
Route::post('/registro-directo', [PersonaController::class, 'registrarDirecto']);

// ─── Catálogos Globales ───────────────────────────────────────────────────────
Route::prefix('catalogos')->group(function () {
    Route::get('/tipos-colegio', [CatalogosController::class, 'tiposColegio']);
    Route::get('/paises', [CatalogosController::class, 'paises']);
    Route::get('/niveles-academicos', [CatalogosController::class, 'nivelesAcademicos']);
    Route::get('/tipos-posgrado', [CatalogosController::class, 'tiposPosgrado']);
    Route::get('/generos', [CatalogosController::class, 'generos']);
    Route::get('/estados-civiles', [CatalogosController::class, 'estadosCiviles']);
    Route::get('/tipos-evento', [CatalogosController::class, 'tiposEvento']);
    Route::get('/tipos-participacion-evento', [CatalogosController::class, 'tiposParticipacionEvento']);
    Route::get('/tipos-reconocimiento', [CatalogosController::class, 'tiposReconocimiento']);
    Route::get('/tipos-produccion-intelectual', [CatalogosController::class, 'tiposProduccionIntelectual']);
    Route::get('/tipos-membresia', [CatalogosController::class, 'tiposMembresia']);
    Route::get('/niveles-idioma', [CatalogosController::class, 'nivelesIdioma']);
    Route::get('/idiomas', [CatalogosController::class, 'idiomas']);
    Route::get('/cargos', [CatalogosController::class, 'cargos']);
    Route::get('/tipos-contrato', [CatalogosController::class, 'tiposContrato']);
    Route::get('/departamentos', [CatalogosController::class, 'departamentos']);
});

// ─── Rutas protegidas ─────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/force-change-password', [AuthController::class, 'forceChangePassword']);

    // ── Usuarios (acceso al sistema) ──────────────────────────
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // ── Personas (identidad centralizada) ────────────────────
    Route::get('/personas/mi-hoja-vida', [PersonaController::class, 'miHojaVida']);
    Route::post('/personas/mi-hoja-vida', [PersonaController::class, 'actualizarMiHojaVida']); // POST por form-data con archivos
    Route::get('/personas', [PersonaController::class, 'index']);
    Route::post('/personas', [PersonaController::class, 'store']);
    Route::get('/personas/buscar-ci', [PersonaController::class, 'buscarPorCi']);
    Route::get('/personas/{id}', [PersonaController::class, 'show']);
    Route::put('/personas/{id}', [PersonaController::class, 'update']);
    Route::delete('/personas/{id}', [PersonaController::class, 'destroy']);

    // ── Empleados y Contratos (Nuevo Módulo) ──────────────────
    Route::post('/empleados/store-massive', [\App\Http\Controllers\EmpleadoController::class, 'storeMassive']);
    Route::apiResource('empleados', \App\Http\Controllers\EmpleadoController::class);
    Route::apiResource('contratos', ContratoController::class);

    // ── Legajo (Parte A) ──
    Route::prefix('empleados/{empleado}/legajo')->group(function () {
        // Bachillerato (Especial: Único, no tiene index ni destroy)
        Route::get('bachillerato', [LegajoBachilleratoController::class, 'show']);
        Route::post('bachillerato', [LegajoBachilleratoController::class, 'store']);
        Route::put('bachillerato', [LegajoBachilleratoController::class, 'update']);

        // Otros módulos (Colecciones)
        Route::apiResource('formacion-academica', LegajoFormacionAcademicaController::class);
        Route::apiResource('posgrado', LegajoPosgradoController::class);
        Route::apiResource('experiencia-docencia', LegajoExperienciaDocenciaController::class);
        Route::apiResource('experiencia-profesional', LegajoExperienciaProfesionalController::class);
        Route::apiResource('capacitacion', LegajoCapacitacionController::class);
        Route::apiResource('eventos', LegajoEventosController::class);
        Route::apiResource('reconocimiento', LegajoReconocimientoController::class);
        Route::apiResource('produccion-intelectual', LegajoProduccionIntelectualController::class);
        Route::apiResource('membresias', LegajoMembresiasController::class);
        Route::apiResource('idiomas', LegajoIdiomasController::class);

        // CV Completo (Una sola llamada)
        Route::get('completo', [LegajoCompletoController::class, 'show']);
    });

    // ── Gestión Global de Archivos de Legajo ──────────────────
    Route::post('legajo/archivos', [LegajoArchivoController::class, 'store']);
    Route::get('legajo/archivos/{archivo}', [LegajoArchivoController::class, 'show']);
    Route::delete('legajo/archivos/{archivo}', [LegajoArchivoController::class, 'destroy']);

    // ── Cargos ────────────────────────────────────────────────
    Route::get('/cargos', [CargoController::class, 'index']);
    Route::post('/cargos', [CargoController::class, 'store']);
    Route::put('/cargos/{id}', [CargoController::class, 'update']);
    Route::delete('/cargos/{id}', [CargoController::class, 'destroy']);

    // ── Tipos de Personal ─────────────────────────────────────
    Route::get('/tipos-personal', [CargoController::class, 'tiposPersonal']);
    Route::post('/tipos-personal', [CargoController::class, 'storeTipoPersonal']);
    Route::put('/tipos-personal/{id}', [CargoController::class, 'updateTipoPersonal']);

    // ── Aplicaciones / Sistemas ───────────────────────────────
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::put('/applications/{id}', [ApplicationController::class, 'update']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);

    // ── Sedes ─────────────────────────────────────────────────
    Route::get('/sedes', [SedeController::class, 'index']);
    Route::post('/sedes', [SedeController::class, 'store']);
    Route::put('/sedes/{id}', [SedeController::class, 'update']);
    Route::delete('/sedes/{id}', [SedeController::class, 'destroy']);

    // ── Áreas ─────────────────────────────────────────────────
    Route::get('/areas', [AreaController::class, 'index']);

    // ── Roles ─────────────────────────────────────────────────
    Route::get('/roles', [RolController::class, 'index']);
    Route::post('/roles', [RolController::class, 'store']);
    Route::get('/roles/{id}', [RolController::class, 'show']);
    Route::put('/roles/{id}', [RolController::class, 'update']);
    Route::delete('/roles/{id}', [RolController::class, 'destroy']);

    // ── Configuraciones ───────────────────────────────────────
    Route::post('/settings/registro-directo/toggle', [SettingController::class, 'toggleRegistroDirectoStatus']);

    // ── Documentación / Méritos ───────────────────────────────
    Route::get('/personas/{personaId}/documentos', [DocumentoController::class, 'listPorPersona']);

    // ── Permisos ──────────────────────────────────────────────
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/applications/{id}/permissions', [PermissionController::class, 'byApplication']);
});

// Ruta pública — acceso de empleados por identidad
Route::post('/empleado/verificar-identidad', [EmployeeAccessController::class, 'verificarIdentidad']);

// ── CV PDF (Descarga / Preview) ───────────────────────────────────────────────
Route::get('/empleados/{empleadoId}/cv/descargar', [CvPdfController::class, 'descargar']);
Route::get('/empleados/{empleadoId}/cv/preview', [CvPdfController::class, 'preview']);
