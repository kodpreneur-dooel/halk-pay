<?php

namespace Codepreneur\HalkPayGateway\Contracts;

use Codepreneur\HalkPayGateway\Data\CallbackResult;

interface CallbackProcessor
{
    /**
     * Process callback results inside host application domain.
     */
    public function process(CallbackResult $result): void;
}
