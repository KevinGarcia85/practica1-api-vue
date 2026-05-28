<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        // Validamos que lleguen los productos del carrito
        $request->validate([
            'items' => 'required|array',
        ]);

        // Simulación o guardado en Base de Datos:
        // Aquí puedes procesar el pedido o registrarlo en logs para verificar que llegó
        Log::info('Pedido recibido con éxito:', $request->all());

        return response()->json([
            'status' => 'success',
            'message' => '¡Pedido registrado correctamente en Laravel!',
            'items_recibidos' => count($request->items)
        ], 201);
    }
}