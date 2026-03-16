<?php

use Codepreneur\HalkPayGateway\Http\Controllers\PaymentCallbackController;
use Codepreneur\HalkPayGateway\Http\Controllers\RedirectToGatewayController;
use Illuminate\Support\Facades\Route;

$routeMiddleware = (array)config('halkpay.routes.middleware', ['web']);
$routePrefix = (string)config('halkpay.routes.prefix', 'halkpay');
$routeNamePrefix = (string)config('halkpay.routes.name_prefix', 'halkpay.');

Route::middleware($routeMiddleware)
    ->prefix($routePrefix)
    ->name($routeNamePrefix)
    ->group(function (): void {
        Route::post('/redirect', RedirectToGatewayController::class)->name('redirect');
        Route::post('/callback', PaymentCallbackController::class)
            ->middleware('halkpay.hash')
            ->name('callback');
    });
