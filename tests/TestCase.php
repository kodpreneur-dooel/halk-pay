<?php

namespace Codepreneur\HalkPayGateway\Tests;

use Codepreneur\HalkPayGateway\HalkPayGatewayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            HalkPayGatewayServiceProvider::class,
        ];
    }
}
