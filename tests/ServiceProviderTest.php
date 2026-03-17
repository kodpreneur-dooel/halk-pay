<?php

namespace Codepreneur\HalkPayGateway\Tests;

class ServiceProviderTest extends TestCase
{
    public function test_it_registers_package_config(): void
    {
        $this->assertTrue($this->app->bound('config'));
        $this->assertTrue($this->app['config']->has('halkpay'));
    }
}
