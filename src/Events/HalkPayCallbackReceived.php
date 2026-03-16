<?php

namespace Codepreneur\HalkPayGateway\Events;

use Codepreneur\HalkPayGateway\Data\CallbackResult;

class HalkPayCallbackReceived
{
    public function __construct(public readonly CallbackResult $result)
    {
    }
}
