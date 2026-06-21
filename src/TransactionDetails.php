<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Core\ResponseStatus;
use Mds\Natcash\Exception\ApiException;

final class TransactionDetails
{
    public const RESPONSE_CODE_SUCCESS = 1;

    public const RESPONSE_CODE_FAILED = -1;

    public const RESPONSE_CODE_UNKNOWN = -3;

    /**
     * orderId - OrderId provided by your app.
     */
    private string $orderId;

    /**
     * transactionId - TransactionId provided by Moncash.
     */
    private string $transactionId;

    /**
     * amount - Amount paid by the payer.
     *
     * @var float Amount paid by the payer.
     */
    private float $amount;

    /**
     * payer - Payer's phone number.
     */
    private string $payer;

    /**
     * status - Status of the payment.
     *
     * @var bool Is Payment Successful
     */
    private bool $isSuccessful;

    /**
     * __construct - Create TransactionDetails Object
     *
     * @param  string  $orderId  OrderId provided by your app.
     * @param  string  $transactionId  TransactionId provided by Moncash.
     * @param  float  $amount  Amount paid by the payer.
     * @param  string  $payer  Payer's phone number.
     * @param  bool  $isSuccessful  Is Payment Successful
     */
    public function __construct(string $orderId, string $transactionId, float $amount, string $payer, bool $isSuccessful)
    {
        $this->orderId = $orderId;
        $this->transactionId = $transactionId;
        $this->amount = $amount;
        $this->payer = $payer;
        $this->isSuccessful = $isSuccessful;
    }

    /**
     * fromResponse - Create TransactionDetails Object from Response
     *
     * @param  \Psr\Http\Message\ResponseInterface  $res  Response from Moncash
     * @return TransactionDetails TransactionDetails Object
     */
    public static function fromResponse(\Psr\Http\Message\ResponseInterface $res): self
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
