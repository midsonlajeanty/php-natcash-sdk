<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Core\Constants;
use Mds\Natcash\Core\Core;
use Mds\Natcash\Exception\NatcashException;

/**
 * Natcash
 *
 * @version 1.0.0
 *
 * @license MIT
 * @author Mds <midsonlajeanty@proton.me>
 */
final class Natcash extends Core
{
    /**
     * makePayment - Process Payment
     *
     * @param  PaymentRequest  $paymentRequest  Payment Request Object
     * @return PaymentResponse Payment Response Object
     *
     * @throws NatcashException
     */
    public function makePayment(PaymentRequest $paymentRequest): PaymentResponse
    {
        try {
            $response = $this->getclient()->request('POST', Constants::PAYMENT_URI, [
                'json' => $this->getPaymentDataArray($paymentRequest),
            ]);

            return PaymentResponse::fromResponse($response);
        } catch (\GuzzleHttp\Exception\ClientException $clientException) {
            throw new NatcashException(
                $clientException->getResponse()->getBody()->getContents()
            );
        }
    }

    /**
     * verifyPayloadSignature - Verify webhook Payload Signature
     *
     * @param  string  $orderId  Order Id
     * @param  int $code  Response Code { 1: Success, -3: Unknown , -1: Failed }
     * @param  string  $signature  Signature to validate
     * @return bool Signature Validity
     */
    public function verifyPayloadSignature(string $orderId, int $code, string $signature): bool
    {
        return $this->isPayloadSignatureValid($orderId, $code, $signature);
    }

    /**
     * getTransactionDetailsByOrderId - Get Payment Details by Order Id
     *
     * @param  string  $orderId  Order Id
     * @param  string|null  $requestId  Request ID
     * @return TransactionDetails Payment Details Object
     *
     * @throws NatcashException
     */
    public function getTransactionDetailsByOrderId(string $orderId, ?string $requestId = null): TransactionDetails
    {
        try {
            $response = $this->getclient()->request('POST', Constants::TRANSACTION_DETAILS_URI, [
                'json' => $this->getTransactionDataArray($orderId, $requestId),
            ]);

            return TransactionDetails::fromResponse($response);
        } catch (\GuzzleHttp\Exception\ClientException $clientException) {
            throw new NatcashException(
                $clientException->getResponse()->getBody()->getContents()
            );
        }
    }
}
