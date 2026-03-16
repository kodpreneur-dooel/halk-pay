<?php

namespace Codepreneur\HalkPayGateway\Contracts;

interface TransactionReferenceResolver
{
    /**
     * Resolve any host-application transaction reference by OID.
     */
    public function resolveByOid(string $oid): mixed;
}
