<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::all(), 200);
    }

    public function productosPorCategoria($id)
    {
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        // Retorna los productos de esa categoría incluyendo los datos de la categoría (Eager Loading)
        $productos = $categoria->productos()->with('categoria')->get();
        return response()->json($productos, 200);
    }
}