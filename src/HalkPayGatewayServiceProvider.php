<?php

namespace Codepreneur\HalkPayGateway;

use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;
use Codepreneur\HalkPayGateway\Contracts\InstallmentResolver;
use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;
use Codepreneur\HalkPayGateway\Http\Middleware\ValidateHalkPayCallbackHash;
use Codepreneur\HalkPayGateway\Support\NullCallbackProcessor;
use Codepreneur\HalkPayGateway\Support\NullTransactionReferenceResolver;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HalkPayGatewayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('halkpay')
            ->hasConfigFile('halkpay')
            ->hasViews('halkpay');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(TransactionReferenceResolver::class, fn () => new NullTransactionReferenceResolver());
        $this->app->bind(InstallmentResolver::class, fn ($app) => $app->make(TransactionReferenceResolver::class));
        $this->app->bind(CallbackProcessor::class, fn () => new NullCallbackProcessor());
    }

    public function packageBooted(): void
    {
        Route::aliasMiddleware('halkpay.hash', ValidateHalkPayCallbackHash::class);

        if ((bool) config('halkpay.routes.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        $this->publishes([
            __DIR__.'/../config/halkpay.php' => config_path('halkpay.php'),
        ], 'halkpay-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/halkpay'),
        ], 'halkpay-views');
    }
}
