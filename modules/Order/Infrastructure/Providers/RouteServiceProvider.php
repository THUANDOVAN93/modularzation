<?php

namespace Modules\Order\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                    ->group(__DIR__ . '/../../Ui/routes.php');
        });
    }
}
