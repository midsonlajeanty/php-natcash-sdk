<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Mds\Natcash\Exception\ApiException;
use Mds\Natcash\TransactionDetails;

test('transaction details object creation', function (): void {
    $details = new TransactionDetails(
        'order123',
        'trans123',
        100.0,
        '50930000000',
        true
    );

    expect($details->getOrderId())->toBe('order123');
    expect($details->getTransactionId())->toBe('trans123');
    expect($details->getAmount())->toBe(100.0);
    expect($details->getPayer())->toBe('50930000000');
    expect($details->isSuccessful())->toBeTrue();
});

test('transaction details from response', function (): void {
    $response = new Response(200, [], json_encode([
        'status' => 0,
        'code' => 'SUCCESS',
        'message' => 'Success',
        'data' => [
            'orderNumber' => 'order123',
            'transId' => 'trans123',
            'amount' => 100.0,
            'toPhone' => '50930000000',
            'responseCode' => 1,
        ],
    ]));

    $details = TransactionDetails::fromResponse($response);

    expect($details->getOrderId())->toBe('order123');
    expect($details->getTransactionId())->toBe('trans123');
    expect($details->getAmount())->toBe(100.0);
    expect($details->getPayer())->toBe('50930000000');
    expect($details->isSuccessful())->toBeTrue();
});

test('transaction details from failed response throws exception', function (): void {
    $response = new Response(200, [], json_encode([
        'status' => 1,
        'code' => 'ERROR',
        'message' => 'Some error',
    ]));

    TransactionDetails::fromResponse($response);
})->throws(ApiException::class);
