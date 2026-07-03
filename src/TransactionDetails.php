<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Core\ResponseStatus;
use Mds\Natcash\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

final readonly class TransactionDetails
{
    public const RESPONSE_CODE_SUCCESS = 1;

    public const RESPONSE_CODE_FAILED = -1;

    public const RESPONSE_CODE_UNKNOWN = -3;

    /**
     * __construct - Create TransactionDetails Object
     *
     * @param  string  $orderId  OrderId provided by your app.
     * @param  string  $transactionId  TransactionId provided by Moncash.
     * @param  float  $amount  Amount paid by the payer.
     * @param  string  $payer  Payer's phone number.
     * @param  bool  $isSuccessful  Is Payment Successful
     */
    public function __construct(
        private string $orderId,
        private string $transactionId,
        /**
         * amount - Amount paid by the payer.
         */
        private float $amount,
        private string $payer,
        /**
         * status - Status of the payment.
         */
        private bool $isSuccessful
    ) {}

    /**
     * fromResponse - Create TransactionDetails Object from Response
     *
     * @param  ResponseInterface  $res  Response from Moncash
     * @return TransactionDetails TransactionDetails Object
     */
    public static function fromResponse(ResponseInterface $res): self
    {
        $respons = ResponseStatus::fromResponse($res);

        if (! $respons->isSuccess()) {
            throw new ApiException($respons->getMessage());
        }

        $body = $respons->getData();

        return new self(
            $body->data->orderNumber,
            $body->data->transId,
            (float) ($body->data->amount),
            $body->data->toPhone,
            $body->data->responseCode === self::RESPONSE_CODE_SUCCESS
        );
    }

    /**
     * getOrderId - Get OrderId
     *
     * @return string OrderId provided by your app
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * getTransactionId - Get TransactionId
     *
     * @return string TransactionId provided by Moncash
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * amount - Get Amount paid by the payer.
     *
     * @return float Amount paid by the payer.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * getPayer - Get Payer
     *
     * @return string Payer's phone number
     */
    public function getPayer(): string
    {
        return $this->payer;
    }

    /**
     * isSuccessful - Verify Status
     *
     * @return bool Payment Successful
     */
    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * toArray - Payment Details to Array
     *
     * @return array{orderId: string, transactionId: string, amount: float, payer: string, isSuccessful: bool} Transaction details
     */
    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'transactionId' => $this->transactionId,
            'amount' => $this->amount,
            'payer' => $this->payer,
            'isSuccessful' => $this->isSuccessful,
        ];
    }
}
