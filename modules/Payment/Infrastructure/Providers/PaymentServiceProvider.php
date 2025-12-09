<?php

namespace Modules\Payment\Infrastructure\Providers;

use Carbon\Laravel\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config.php', 'payment');

        $this->loadRoutesFrom(__DIR__ . '/../../routes.php');
    }
}
