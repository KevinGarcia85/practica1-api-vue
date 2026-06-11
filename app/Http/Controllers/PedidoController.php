<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Jobs\EnviarCorreoPedidoJob;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Crear pedido y despachar la tarea en segundo plano
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_email' => 'required|email',
            'total'         => 'required|numeric'
        ]);

        $pedido = Pedido::create($validated);

        // Despachar el Job a la cola
        EnviarCorreoPedidoJob::dispatch($pedido);

        return response()->json([
            'message' => 'Pedido registrado. Procesando notificación en cola...',
            'pedido'  => $pedido
        ], 201);
    }

    // Consultar el estado del pedido (Polling)
    public function show($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json($pedido, 200);
    }
}