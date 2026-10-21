<?php

declare(strict_types=1);

use Mds\Natcash\Core\Constants;
use Mds\Natcash\Exception\InvalidConfigException;
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

test('resolving the gateway with an empty callback url throws', function (): void {
    config()->set('natcash.callback_url', '');

    app(NatcashInterface::class);
})->throws(InvalidConfigException::class, 'callbackUrl');

test('the resolved client carries the configured timeouts', function (): void {
    config()->set('natcash.timeout', 7.5);
    config()->set('natcash.connect_timeout', 3);

    $client = app(NatcashInterface::class)->getClient();

    expect($client->getConfig('timeout'))->toBe(7.5)
        ->and($client->getConfig('connect_timeout'))->toBe(3.0);
});

test('the resolved client falls back to the default timeouts', function (): void {
    config()->set('natcash.timeout');
    config()->set('natcash.connect_timeout');

    $client = app(NatcashInterface::class)->getClient();

    expect($client->getConfig('timeout'))->toBe((float) Constants::DEFAULT_TIMEOUT)
        ->and($client->getConfig('connect_timeout'))->toBe((float) Constants::DEFAULT_CONNECT_TIMEOUT);
});

test('the resolved client keeps the gateway base uri', function (): void {
    expect((string) app(NatcashInterface::class)->getClient()->getConfig('base_uri'))->toBe(Constants::SANDBOX_URL);

    app()->forgetInstance(NatcashInterface::class);
    config()->set('natcash.debug', false);

    expect((string) app(NatcashInterface::class)->getClient()->getConfig('base_uri'))->toBe(Constants::LIVE_URL);
});
