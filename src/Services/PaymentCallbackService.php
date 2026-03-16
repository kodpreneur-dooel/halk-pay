<?php

namespace Codepreneur\HalkPayGateway\Services;

use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;
use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;
use Codepreneur\HalkPayGateway\Data\CallbackResult;
use Codepreneur\HalkPayGateway\Events\HalkPayCallbackReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class PaymentCallbackService
{
    public function __construct(
        private readonly TransactionReferenceResolver $resolver,
        private readonly CallbackProcessor            $processor,
    )
    {
    }

    public function handle(Request $request): CallbackResult
    {
        $oid = (string)$request->input('oid', '');

        if ($oid === '') {
            throw new InvalidArgumentException('Missing oid parameter.');
        }

        $result = new CallbackResult(
            oid: $oid,
            successful: $this->isSuccessful($request),
            status: $this->isSuccessful($request) ? 'paid' : 'failed',
            reference: $this->resolver->resolveByOid($oid),
            payload: $request->all(),
        );

        $this->processor->process($result);
        Event::dispatch(new HalkPayCallbackReceived($result));

        return $result;
    }

    public function isSuccessful(Request $request): bool
    {
        return $request->input('Response') === 'Approved'
            && $request->input('ProcReturnCode') === '00';
    }
}
