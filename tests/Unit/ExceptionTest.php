<?php

declare(strict_types=1);

use Mds\Natcash\Exception\ApiException;
use Mds\Natcash\Exception\InvalidConfigException;
use Mds\Natcash\Exception\InvalidPaymentRequestException;
use Mds\Natcash\Exception\NatcashException;

it('exception hierarchy extends base NatcashException', function (): void {
    expect(new InvalidConfigException('x'))->toBeInstanceOf(NatcashException::class);
    expect(new InvalidPaymentRequestException('x'))->toBeInstanceOf(NatcashException::class);
    expect(new ApiException('x'))->toBeInstanceOf(NatcashException::class);
});

it('base NatcashException is still catchable for subclasses', function (): void {
    $caught = false;
    try {
        throw new InvalidConfigException('boom');
    } catch (NatcashException) {
        $caught = true;
    }
    expect($caught)->toBeTrue();
});
