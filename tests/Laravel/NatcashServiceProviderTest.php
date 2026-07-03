<?php

declare(strict_types=1);

use Mds\Natcash\Laravel\Facades\Natcash;
use Mds\Natcash\Natcash as NatcashGateway;
use Mds\Natcash\NatcashInterface;

test('the container resolves a configured NatCash gateway', function (): void {
    expect(app(NatcashInterface::class))->toBeInstanceOf(NatcashGateway::class);
});

test('the gateway is a singleton reachable through the natcash alias', function (): void {
    expect(app('natcash'))->toBe(app(NatcashInterface::class));
});

test('the facade proxies to the bound gateway', function (): void {
    expect(Natcash::getFacadeRoot())->toBeInstanceOf(NatcashGateway::class);
});
