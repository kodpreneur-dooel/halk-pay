<?php

namespace Codepreneur\HalkPayGateway\Data;

class CallbackResult
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public readonly string $oid,
        public readonly bool   $successful,
        public readonly string $status,
        public readonly mixed  $reference,
        public readonly array  $payload,
    )
    {
    }
}
