<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Aquí mapearemos la Policy en el siguiente paso
        \App\Models\Producto::class => \App\Policies\ProductoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // GATES GRANULARES
        Gate::define('crear-producto', function (User $user) {
            return $user->esAdmin() || $user->esEditor();
        });

        Gate::define('editar-producto', function (User $user) {
            return $user->esAdmin() || $user->esEditor();
        });

        Gate::define('eliminar-producto', function (User $user) {
            return $user->esAdmin(); // Únicamente los Administradores
        });
    }
}