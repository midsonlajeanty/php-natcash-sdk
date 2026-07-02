<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Exception\NatcashException;

/**
 * NatcashInterface - Public contract of the NatCash gateway.
 *
 * Type-hint against this interface in your application code so it can be
 * mocked in tests (the Natcash class is final by design). The Natcash class
 * is the production implementation.
 */
interface NatcashInterface
{
    /**
     * makePayment - Process a payment.
     *
     * @throws NatcashException
     */
    public function makePayment(PaymentRequest $paymentRequest): PaymentResponse;

    /**
     * verifyPayloadSignature - Verify a callback payload signature.
     */
    public function verifyPayloadSignature(string $orderId, int $code, string $signature): bool;

    /**
     * getTransactionDetailsByOrderId - Retrieve transaction details by order id.
     *
     * @throws NatcashException
     */
    public function getTransactionDetailsByOrderId(string $orderId, ?string $requestId = null): TransactionDetails;
}
