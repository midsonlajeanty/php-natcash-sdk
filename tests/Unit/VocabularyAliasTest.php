<?php

declare(strict_types=1);

use Mds\Natcash\PaymentRequest;
use Mds\Natcash\PaymentResponse;

it('PaymentRequest exposes getOrderId aligned accessor', function (): void {
    $req = new PaymentRequest('ORDER-1', 10.0);
    expect($req->getOrderId())->toBe('ORDER-1');
    expect($req->getOrderId())->toBe($req->getOrderNumber());
});

it('PaymentResponse exposes getExpiresAt aligned accessor', function (): void {
    $res = new PaymentResponse('https://pay.example', 300);
    expect($res->getExpiresAt())->toBe(300);
    expect($res->getExpiresAt())->toBe($res->getExpiredAt());
});
