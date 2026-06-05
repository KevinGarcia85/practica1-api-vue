<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Vacía la base de datos simulada en cada prueba

    public function test_usuario_puede_registrarse(): void
    {
        $response = $this->postJson('/api/register', [
            'name'     => 'Juan López',
            'email'    => 'juan@test.com',
            'password' => 'password123',
        ]);

        // Se adapta al return response()->json(['message', 'user']) de tu AuthController
        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'user']);
                 
        $this->assertDatabaseHas('users', ['email' => 'juan@test.com']);
    }

    public function test_login_con_credenciales_incorrectas(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'noexiste@test.com',
            'password' => 'wrongpass',
        ]);
        
        $response->assertStatus(401);
    }
}