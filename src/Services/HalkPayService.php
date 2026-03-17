<?php

namespace Codepreneur\HalkPayGateway\Services;

use Illuminate\Support\Str;

class HalkPayService
{
    /**
     * @param array<string, string|int|float|null> $overrides
     * @return array<string, string|int|float|null>
     */
    public function buildPayload(array $overrides = []): array
    {
        $storeKey = config('halkpay.store_key');

        $defaults = [
            'amount' => '0.00',
            'BillToName' => '',
            'callbackUrl' => '',
            'clientId' => config('halkpay.client_id'),
            'currency' => config('halkpay.currency'),
            'failUrl' => config('halkpay.fail_url'),
            'hashAlgorithm' => 'ver3',
            'Instalment' => '',
            'lang' => config('halkpay.lang'),
            'oid' => Str::upper(Str::random(12)),
            'okUrl' => config('halkpay.success_url'),
            'refreshtime' => '5',
            'rnd' => Str::random(24),
            'storetype' => config('halkpay.store_type'),
            'TranType' => config('halkpay.transaction_type'),
        ];

        $params = array_merge($defaults, $overrides);

        $hashOrder = [
            'amount', 'BillToName', 'callbackUrl', 'clientId', 'currency', 'failUrl', 'hashAlgorithm',
            'Instalment', 'lang', 'oid', 'okUrl', 'refreshtime', 'rnd', 'storetype', 'TranType',
        ];

        $hashString = '';
        foreach ($hashOrder as $key) {
            $hashString .= ($params[$key] ?? '') . '|';
        }

        $hashString = rtrim($hashString, '|') . '|' . $storeKey;
        $params['hash'] = base64_encode(hash('sha512', $hashString, true));

        return $params;
    }

    public function getGatewayUrl(): string
    {
        return (string)config('halkpay.gateway_url');
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function verifyCallbackHash(array $payload): bool
    {
        $hash = trim((string)($payload['HASH'] ?? ''));
        $hashParams = trim((string)($payload['HASHPARAMS'] ?? ''));
        $hashParamsVal = (string)($payload['HASHPARAMSVAL'] ?? '');

        if ($hash === '' || $hashParams === '' || $hashParamsVal === '') {
            return false;
        }

        $storeKey = trim((string)config('halkpay.store_key'));

        if ($storeKey === '') {
            return false;
        }

        $computed = base64_encode(
            hash('sha512', $hashParamsVal . $storeKey, true)
        );

        return hash_equals($computed, $hash);
    }
}
