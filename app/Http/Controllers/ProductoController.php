<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;


class ProductoController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        return response()->json(Producto::all(), 200);
    }

// Cambia Request por StoreProductoRequest
public function store(StoreProductoRequest $request)
{
    // Laravel ya validó de forma automática aquí. Si falla, enviará el error 422.
    $producto = Producto::create($request->validated());
    return response()->json($producto, 201);
}

    // Mostrar un producto individual
    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($producto, 200);
    }

    // Actualizar un producto existente
// Cambia Request por UpdateProductoRequest
public function update(UpdateProductoRequest $request, $id)
{
    $producto = Producto::find($id);
    if (!$producto) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    $producto->update($request->validated());
    return response()->json($producto, 200);
}

    // Eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}