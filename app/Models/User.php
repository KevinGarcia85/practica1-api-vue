<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol', // Asegúrate de incluir 'rol' en la asignación masiva
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- HELPERS DE ROLES (Práctica 08) ---
    public function esAdmin(): bool { 
        return $this->rol === 'admin'; 
    }

    public function esEditor(): bool { 
        return $this->rol === 'editor'; 
    }
}