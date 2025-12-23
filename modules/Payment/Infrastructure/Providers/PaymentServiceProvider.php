<?php

namespace Modules\Payment\Infrastructure\Providers;

use Carbon\Laravel\ServiceProvider;
use Modules\Payment\PayBuddyGateway;
use Modules\Payment\PayBuddySdk;
use Modules\Payment\PaymentGateway;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config.php', 'payment');

        $this->loadRoutesFrom(__DIR__ . '/../../routes.php');
        $this->app->bind(PaymentGateway::class, fn() => new PayBuddyGateway(new PayBuddySdk()));
    }
}
