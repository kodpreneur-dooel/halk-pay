<?php

namespace Codepreneur\HalkPayGateway\Http\Middleware;

use Closure;
use Codepreneur\HalkPayGateway\Services\HalkPayService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateHalkPayCallbackHash
{
    public function __construct(private readonly HalkPayService $halkPayService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->halkPayService->verifyCallbackHash($request->all())) {
            abort(400, 'Invalid callback signature.');
        }

        return $next($request);
    }
}
