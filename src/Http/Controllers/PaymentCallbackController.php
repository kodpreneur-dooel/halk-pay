<?php

namespace Codepreneur\HalkPayGateway\Http\Controllers;

use Codepreneur\HalkPayGateway\Services\PaymentCallbackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentCallbackController extends Controller
{
    public function __invoke(Request $request, PaymentCallbackService $service): JsonResponse|RedirectResponse
    {
        $result = $service->handle($request);

        if (config('halkpay.callback.response_mode') === 'redirect') {
            return redirect()->to(
                $result->successful
                    ? (string)config('halkpay.callback.success_redirect_to', '/')
                    : (string)config('halkpay.callback.fail_redirect_to', '/'),
            );
        }

        return response()->json([
            'message' => 'Callback accepted.',
            'status' => $result->status,
            'oid' => $result->oid,
            'successful' => $result->successful,
        ]);
    }
}
