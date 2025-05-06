<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * O namespace do seu controlador.
     *
     * Se definido, esse valor será automaticamente aplicado às rotas nos arquivos de rotas.
     *
     * Laravel 9+ não usa mais namespace por padrão.
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Caminho para a "home" da aplicação após o login.
     */
    public const HOME = '/home';

    /**
     * Define as rotas da aplicação.
     */
    public function boot(): void
    {
        // Aqui você pode definir middlewares, grupos e arquivos de rota
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
