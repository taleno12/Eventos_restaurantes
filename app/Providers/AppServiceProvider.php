<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'pedido_gastrobar' => \App\Models\PedidoGastrobar::class,
            'pedido'           => \App\Models\Pedido::class,
            'usuario'          => \App\Models\User::class,
        ]);
    }

    protected $policies = [
        Review::class => ReviewPolicy::class,
    ];
}
