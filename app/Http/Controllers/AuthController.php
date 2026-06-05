<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro de nuevos usuarios desde el Frontend.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol'      => 'cliente', // Rol base asignado automáticamente por defecto
        ]);

        return response()->json([
            'message' => 'Usuario registrado de forma exitosa',
            'user'    => $user
        ], 201);
    }

    /**
     * Inicio de sesión y generación de Tokens de Sanctum.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json(['message' => 'Credenciales de acceso incorrectas'], 401);
        }

        $user = Auth::user();
        
        // Creamos el token de Sanctum para las cabeceras asíncronas de Axios
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user
        ], 200);
    }

    /**
     * Retorna los datos del usuario logueado junto con su matriz de permisos dinámicos.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        // Estructura condicional lógica que determina los permisos basados en el rol del usuario
        $permisos = [
            'crear'    => in_array($user->rol, ['admin', 'editor']),
            'editar'   => in_array($user->rol, ['admin', 'editor']),
            'eliminar' => $user->rol === 'admin', // Únicamente el administrador puede borrar
        ];

        return response()->json([
            'user'     => $user,
            'permisos' => $permisos
        ], 200);
    }

    /**
     * Cierre de sesión y revocación del token actual.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente y token revocado'
        ], 200);
    }
}