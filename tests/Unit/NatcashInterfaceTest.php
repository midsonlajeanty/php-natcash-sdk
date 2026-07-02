<?php

declare(strict_types=1);

use Mds\Natcash\Natcash;
use Mds\Natcash\NatcashInterface;
use Mds\Natcash\PaymentRequest;
use Mds\Natcash\PaymentResponse;
use Mockery\MockInterface;

test('Natcash implements NatcashInterface', function (): void {
    expect(class_implements(Natcash::class))->toContain(NatcashInterface::class);
});

test('NatcashInterface can stand in for the gateway as a test double', function (): void {
    $response = new PaymentResponse('https://pay.url', 1700000000);

    /** @var NatcashInterface&MockInterface $gateway */
    $gateway = Mockery::mock(NatcashInterface::class);
    $gateway->shouldReceive('makePayment')
        ->once()
        ->andReturn($response);

    expect($gateway)->toBeInstanceOf(NatcashInterface::class);
    expect($gateway->makePayment(new PaymentRequest('order1', 100, null, 'msisdn')))->toBe($response);
});
