<?php

namespace Codepreneur\HalkPayGateway\Http\Controllers;

use Codepreneur\HalkPayGateway\Services\HalkPayService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RedirectToGatewayController extends Controller
{
    /**
     * Build a HalkPay auto-submit page.
     *
     * Required inputs: amount
     * Optional inputs: oid, customer_name, callback_url, success_url, fail_url, lang, currency
     */
    public function __invoke(Request $request, HalkPayService $service): View
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'oid' => ['nullable', 'string', 'max:128'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'callback_url' => ['nullable', 'url', 'max:500'],
            'success_url' => ['nullable', 'url', 'max:500'],
            'fail_url' => ['nullable', 'url', 'max:500'],
            'lang' => ['nullable', 'string', 'max:8'],
            'currency' => ['nullable', 'string', 'max:8'],
            'transaction_type' => ['nullable', 'string', 'max:32'],
            'installment' => ['nullable', 'string', 'max:8'],
            'bill_to_name' => ['nullable', 'string', 'max:255'],
        ]);

        $oid = $data['oid'] ?? (string)Str::ulid();

        $params = $service->buildPayload([
            'amount' => number_format((float)$data['amount'], 2, '.', ''),
            'oid' => $oid,
            'BillToName' => (string)($data['bill_to_name'] ?? $data['customer_name'] ?? ''),
            'callbackUrl' => (string)($data['callback_url'] ?? route('halkpay.callback')),
            'okUrl' => (string)($data['success_url'] ?? config('halkpay.success_url')),
            'failUrl' => (string)($data['fail_url'] ?? config('halkpay.fail_url')),
            'lang' => (string)($data['lang'] ?? config('halkpay.lang')),
            'currency' => (string)($data['currency'] ?? config('halkpay.currency')),
            'TranType' => (string)($data['transaction_type'] ?? config('halkpay.transaction_type')),
            'Instalment' => (string)($data['installment'] ?? ''),
            'refreshtime' => '0',
        ]);

        return view('halkpay::payments.redirect', [
            'url' => $service->getGatewayUrl(),
            'params' => $params,
        ]);
    }
}
