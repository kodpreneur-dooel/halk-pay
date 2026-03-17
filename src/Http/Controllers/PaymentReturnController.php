<?php

namespace Codepreneur\HalkPayGateway\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentReturnController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $successful = (string)$request->input('Response') === 'Approved'
            && (string)$request->input('ProcReturnCode') === '00';

        $target = $successful
            ? (string)config('halkpay.callback.success_redirect_to', '/')
            : (string)config('halkpay.callback.fail_redirect_to', '/');

        $query = array_filter([
            'oid' => $request->input('oid'),
            'status' => $successful ? 'paid' : 'failed',
            'error_code' => $request->input('ErrorCode'),
            'error_message' => $request->input('ErrMsg'),
        ], fn($value) => filled($value));

        return redirect()->to($target . (count($query) ? '?' . http_build_query($query) : ''));
    }
}
