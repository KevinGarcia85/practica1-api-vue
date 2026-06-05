<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class ProductoPolicy
{
    public function create(User $user): bool
    {
        return Gate::allows('crear-producto');
    }

    public function update(User $user, Producto $producto): bool
    {
        return Gate::allows('editar-producto');
    }

    public function delete(User $user, Producto $producto): bool
    {
        return Gate::allows('eliminar-producto');
    }
}