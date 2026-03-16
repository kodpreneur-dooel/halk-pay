<?php

namespace Codepreneur\HalkPayGateway\Support;

use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;

class NullTransactionReferenceResolver implements TransactionReferenceResolver
{
    public function resolveByOid(string $oid): mixed
    {
        return null;
    }
}
