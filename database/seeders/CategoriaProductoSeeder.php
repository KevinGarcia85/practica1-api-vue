<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Str;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear las Categorías fijas
        $categorias = [
            ['nombre' => 'Electrónica', 'descripcion' => 'Dispositivos, gadgets y tecnología.'],
            ['nombre' => 'Ropa y Moda', 'descripcion' => 'Prendas de vestir y accesorios.'],
            ['nombre' => 'Hogar y Cocina', 'descripcion' => 'Muebles, decoración y utensilios.'],
            ['nombre' => 'Deportes', 'descripcion' => 'Artículos deportivos y fitness.']
        ];

        foreach ($categorias as $cat) {
            $categoriaCreada = Categoria::create([
                'nombre' => $cat['nombre'],
                'slug' => Str::slug($cat['nombre']),
                'descripcion' => $cat['descripcion']
            ]);

            // 2. Crear 5 productos automáticos para cada categoría
            for ($i = 1; $i <= 5; $i++) {
                Producto::create([
                    'categoria_id' => $categoriaCreada->id,
                    'nombre' => "Producto " . $cat['nombre'] . " " . $i,
                    'descripcion' => "Esta es la descripción detallada del producto número " . $i . " perteneciente a la categoría " . $cat['nombre'] . ".",
                    'precio' => rand(100, 1500) / 10,
                    'stock' => rand(10, 100),
                    'imagen' => null
                ]);
            }
        }
    }
}