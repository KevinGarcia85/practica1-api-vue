<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;

// --- RUTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Rutas del catálogo público (Cualquiera puede ver los productos y su detalle)
Route::get('/productos',     [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);


// --- RUTAS PROTEGIDAS (Requieren Token Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    
    // Solo usuarios logueados pueden crear, editar o borrar del inventario
    Route::post('/productos',      [ProductoController::class, 'store']);
    Route::put('/productos/{id}',  [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
});