<?php

declare(strict_types=1);

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mds\Natcash\Config;
use Mds\Natcash\Core\Constants;
use Mds\Natcash\Natcash;
use Mds\Natcash\PaymentRequest;
use Mockery\MockInterface;

test('makePayment success', function (): void {
    $config = new Config('key', 'code', 'functionCode', 'user', 'pass', 'http://cb.url');
    $paymentRequest = new PaymentRequest('order1', 100, null, 'msisdn');

    /** @var ClientInterface&MockInterface $mockClient */
    $mockClient = Mockery::mock(ClientInterface::class);
    $mockClient->shouldReceive('request')
        ->once()
        ->with('POST', Constants::PAYMENT_URI, Mockery::on(fn($args): bool => isset($args['json']['signature'])))
        ->andReturn(new Response(200, [], json_encode([
            'status' => 0,
            'code' => 'SUCCESS',
            'message' => 'Success',
            'url' => 'http://payment.url',
            'expiredAt' => 3600,
        ])));

    $natcash = new Natcash($config, true);
    $natcash->setClient($mockClient);

    $response = $natcash->makePayment($paymentRequest);

    expect($response->getUrl())->toBe('http://payment.url');
});

test('getTransactionDetailsByOrderId success', function (): void {
    $config = new Config('key', 'code', 'functionCode', 'user', 'pass', 'http://cb.url');

    /** @var ClientInterface&MockInterface $mockClient */
    $mockClient = Mockery::mock(ClientInterface::class);
    $mockClient->shouldReceive('request')
        ->once()
        ->with('POST', Constants::TRANSACTION_DETAILS_URI, Mockery::type('array'))
        ->andReturn(new Response(200, [], json_encode([
            'status' => 0,
            'code' => 'SUCCESS',
            'message' => 'Success',
            'data' => [
                'orderNumber' => 'order123',
                'transId' => 'trans123',
                'amount' => 100.0,
                'toPhone' => '50912345678',
                'responseCode' => 1,
            ],
        ])));

    $natcash = new Natcash($config, true);
    $natcash->setClient($mockClient);

    $details = $natcash->getTransactionDetailsByOrderId('order123');

    expect($details->getOrderId())->toBe('order123');
    expect($details->getTransactionId())->toBe('trans123');
    expect($details->getAmount())->toBe(100.0);
    expect($details->getPayer())->toBe('50912345678');
    expect($details->isSuccessful())->toBeTrue();
});
