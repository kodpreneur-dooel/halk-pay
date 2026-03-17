<?php

it('registers package config', function () {
    expect(config()->has('halkpay'))->toBeTrue();
});
