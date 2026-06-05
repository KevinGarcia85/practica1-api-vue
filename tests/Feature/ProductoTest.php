<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_listar_productos(): void
    {
        Producto::factory()->count(3)->create();

        $response = $this->getJson('/api/productos');

        $response->assertStatus(200);
    }

    public function test_puede_crear_producto(): void
    {
        $response = $this->postJson('/api/productos', [
            'nombre' => 'Laptop Dell',
            'precio' => 1299.99,
            'stock'  => 10,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('productos', ['nombre' => 'Laptop Dell']);
    }

    public function test_puede_eliminar_un_producto(): void
    {
        $producto = Producto::factory()->create();

        $response = $this->deleteJson("/api/productos/{$producto->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }
}