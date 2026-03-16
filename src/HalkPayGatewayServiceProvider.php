<?php

namespace Codepreneur\HalkPayGateway;

use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;
use Codepreneur\HalkPayGateway\Contracts\InstallmentResolver;
use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;
use Codepreneur\HalkPayGateway\Http\Middleware\ValidateHalkPayCallbackHash;
use Codepreneur\HalkPayGateway\Support\NullCallbackProcessor;
use Codepreneur\HalkPayGateway\Support\NullTransactionReferenceResolver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HalkPayGatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/halkpay.php', 'halkpay');

        $this->app->bind(TransactionReferenceResolver::class, fn() => new NullTransactionReferenceResolver);
        $this->app->bind(InstallmentResolver::class, fn($app) => $app->make(TransactionReferenceResolver::class));
        $this->app->bind(CallbackProcessor::class, fn() => new NullCallbackProcessor);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/halkpay.php' => config_path('halkpay.php'),
        ], 'halkpay-config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'halkpay');

        Route::aliasMiddleware('halkpay.hash', ValidateHalkPayCallbackHash::class);

        if ((bool)config('halkpay.routes.enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }
}
