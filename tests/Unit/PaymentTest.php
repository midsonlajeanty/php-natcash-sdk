<?php

declare(strict_types=1);

use Mds\Natcash\Exception\InvalidPaymentRequestException;
use Mds\Natcash\PaymentRequest;

test('payment request object creation', function (): void {
    $orderId = 'order123';
    $amount = 100.50;
    $msisdn = '50930000000';
    $timestamp = 123456789;

    $payment = new PaymentRequest(
        $orderId,
        $amount,
        $timestamp,
        $msisdn
    );

    expect($payment->getOrderId())->toBe($orderId);
    expect($payment->getAmount())->toBe($amount);
    expect($payment->getMsisdn())->toBe($msisdn);
    expect($payment->getTimestamp())->toBe($timestamp);
});

test('payment request from array', function (): void {
    $paymentArray = [
        'orderNumber' => 'order123',
        'amount' => 100.50,
        'msisdn' => '50930000000',
        'timestamp' => 123456789,
    ];

    $payment = PaymentRequest::fromArray($paymentArray);

    expect($payment->getOrderId())->toBe('order123');
    expect($payment->getAmount())->toBe(100.50);
    expect($payment->getMsisdn())->toBe('50930000000');
    expect($payment->getTimestamp())->toBe(123456789);
});

test('payment request from array missing fields throws exception', function (): void {
    PaymentRequest::fromArray(['amount' => 10]);
})->throws(InvalidPaymentRequestException::class);
