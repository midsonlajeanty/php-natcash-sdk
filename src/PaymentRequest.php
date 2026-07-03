<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Exception\InvalidPaymentRequestException;

final class PaymentRequest
{
    /**
     * timestamp - Timestamp
     *
     * @var int Transaction creation time. (milisecond)
     */
    private readonly int $timestamp;

    /**
     * requestId - Request ID
     *
     * @var string Request ID
     */
    private string $requestId = '';

    public function __construct(/**
     * orderNumber - Order Number
     *
     * @var string Transaction ID (user to conciliate)
     */
        private readonly string $orderNumber, /**
     * amount - Amount
     *
     * @var float Total transaction amount.
     */
        private readonly float $amount, ?int $timestamp = null, /**
     * msisdn - MSISDN
     *
     * @var ?string User phone number (Ex: 509XXXXXXXXX)
     */
        private readonly ?string $msisdn = null, /**
     * skipPhoneInput - Skip Phone Input
     *
     * @var bool Skip the phone input on gateway
     */
        private readonly bool $skipPhoneInput = false)
    {
        $this->timestamp = $timestamp ?? self::nowInMilliseconds();

        if ($this->getSkipPhoneInput() && (is_null($this->getMsisdn()) || in_array($this->getMsisdn(), ['', '0'], true))) {
            throw new InvalidPaymentRequestException('MSISDN is required when skipPhoneInput is true');
        }
    }

    /**
     * nowInMilliseconds - Get Current Time in Milliseconds
     *
     * @return int Current Time in Milliseconds
     */
    public static function nowInMilliseconds(): int
    {
        return (int) round(microtime(true) * 1000);
    }

    /**
     * from - Create a new PaymentRequest instance from an array
     *
     * @param  array<string, mixed>  $payment  Payment request array
     * @return PaymentRequest PaymentRequest object
     *
     * @throws InvalidPaymentRequestException
     */
    public static function from(array $payment): self
    {
        if (! isset($payment['orderNumber']) || empty($payment['orderNumber'])) {
            throw new InvalidPaymentRequestException('Missing `orderNumber` in payment request array');
        }

        if (! isset($payment['amount']) || empty($payment['amount'])) {
            throw new InvalidPaymentRequestException('Missing `amount` in payment request array');
        }

        if (! is_numeric($payment['amount']) || $payment['amount'] <= 0) {
            throw new InvalidPaymentRequestException('Invalid `amount` in payment request array');
        }

        if (! isset($payment['timestamp']) || empty($payment['timestamp'])) {
            $payment['timestamp'] = self::nowInMilliseconds();
        }

        if (isset($payment['msisdn']) && ! (bool) (preg_match('/^509\d{8}$/', (string) $payment['msisdn']))) {
            throw new InvalidPaymentRequestException('Invalid `msisdn` in payment request array');
        }

        if (isset($payment['skipPhoneInput']) && filter_var($payment['skipPhoneInput'], FILTER_VALIDATE_BOOLEAN) === false) {
            throw new InvalidPaymentRequestException('Invalid `skipPhoneInput` in payment request array');
        }

        return new self(
            $payment['orderNumber'],
            (float) ($payment['amount']),
            (int) ($payment['timestamp']),
            $payment['msisdn'] ?? null,
            $payment['skipPhoneInput'] ?? false,
        );
    }

    /**
     * getOrderId - Get Order ID
     *
     * @return string Order Id
     */
    public function getOrderId(): string
    {
        return $this->orderNumber;
    }

    /**
     * getAmount - Get Amount
     *
     * @return float Total transaction amount.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * getTimestamp - Get Timestamp
     *
     * @return int Transaction creation time. (milisecond)
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * getRequestId - Get Request ID
     *
     * @return string Request ID
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * getMsisdn - Get MSISDN
     *
     * @return ?string MSISDN
     */
    public function getMsisdn(): ?string
    {
        return $this->msisdn;
    }

    /**
     * getSkipPhoneInput - Must Skip Phone Input
     *
     * @return bool Skip the phone input on gateway
     */
    public function getSkipPhoneInput(): bool
    {
        return $this->skipPhoneInput;
    }

    /**
     * setRequestId - Set Request ID
     *
     * @param  string  $requestId  Request ID
     */
    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    /**
     * toArray - Convert Payment Object to Array
     *
     * @return array{orderNumber: string, amount: float, timestamp: int, requestId: string, skipPhoneInput: bool, msisdn?: string} Payment as array
     */
    public function toArray(): array
    {
        $data = [
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
            'timestamp' => $this->timestamp,
            'requestId' => $this->requestId,
            'skipPhoneInput' => $this->skipPhoneInput,
        ];

        if (! is_null($this->msisdn)) {
            $data['msisdn'] = $this->msisdn;
        }

        return $data;
    }
}
