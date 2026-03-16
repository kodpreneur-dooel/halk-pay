<?php

namespace Codepreneur\HalkPayGateway\Support;

use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;
use Codepreneur\HalkPayGateway\Data\CallbackResult;

class NullCallbackProcessor implements CallbackProcessor
{
    public function process(CallbackResult $result): void
    {
        // Intentionally no-op.
    }
}
