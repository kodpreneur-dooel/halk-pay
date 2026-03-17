<?php

namespace Codepreneur\HalkPayGateway\Tests;

use Codepreneur\HalkPayGateway\Services\HalkPayService;

class HalkPayServiceTest extends TestCase
{
    public function test_it_verifies_callback_hash_using_hashparams_order(): void
    {
        config()->set('halkpay.store_key', 'secret-key');

        $payload = [
            'clientid' => '123456',
            'oid' => 'ORDER-1',
            'AuthCode' => 'AUTH123',
            'Response' => 'Approved',
            'ProcReturnCode' => '00',
            'HASHPARAMS' => 'clientid:oid:AuthCode:Response:ProcReturnCode:',
            'HASHPARAMSVAL' => 'tampered-value',
        ];

        $payload['HASH'] = base64_encode(hash(
            'sha512',
            '123456ORDER-1AUTH123Approved00secret-key',
            true
        ));

        $service = new HalkPayService;

        $this->assertTrue($service->verifyCallbackHash($payload));
    }

    public function test_it_rejects_callback_hash_when_payload_value_changes(): void
    {
        config()->set('halkpay.store_key', 'secret-key');

        $payload = [
            'clientid' => '123456',
            'oid' => 'ORDER-1',
            'AuthCode' => 'AUTH123',
            'Response' => 'Approved',
            'ProcReturnCode' => '05',
            'HASHPARAMS' => 'clientid:oid:AuthCode:Response:ProcReturnCode:',
        ];

        $payload['HASH'] = base64_encode(hash(
            'sha512',
            '123456ORDER-1AUTH123Approved00secret-key',
            true
        ));

        $service = new HalkPayService;

        $this->assertFalse($service->verifyCallbackHash($payload));
    }
}
