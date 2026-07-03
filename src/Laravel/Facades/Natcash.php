<?php

declare(strict_types=1);

namespace Mds\Natcash\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Mds\Natcash\NatcashInterface;
use Mds\Natcash\PaymentRequest;
use Mds\Natcash\PaymentResponse;
use Mds\Natcash\TransactionDetails;

/**
 * Laravel facade for the NatCash gateway.
 *
 * @method static PaymentResponse makePayment(PaymentRequest $paymentRequest)
 * @method static bool verifyPayloadSignature(string $orderId, int $code, string $signature)
 * @method static TransactionDetails getTransactionDetailsByOrderId(string $orderId, ?string $requestId = null)
 *
 * @see \Mds\Natcash\Natcash
 */
final class Natcash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NatcashInterface::class;
    }
}
