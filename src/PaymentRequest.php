<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Exception\InvalidPaymentRequestException;

final class PaymentRequest
{
    /**
     * orderNumber - Order Number
     *
     * @var string Transaction ID (user to conciliate)
     */
    private string $orderNumber;

    /**
     * amount - Amount
     *
     * @var float Total transaction amount.
     */
    private float $amount;

    /**
     * msisdn - MSISDN
     *
     * @var ?string User phone number (Ex: 509XXXXXXXXX)
     */
    private ?string $msisdn;

    /**
     * skipPhoneInput - Skip Phone Input
     *
     * @var bool Skip the phone input on gateway
     */
    private bool $skipPhoneInput;

    /**
     * timestamp - Timestamp
     *
     * @var int Transaction creation time. (milisecond)
     */
    private int $timestamp;

    /**
     * requestId - Request ID
     *
     * @var string Request ID
     */
    private string $requestId = '';

    public function __construct(string $orderNumber, float $amount, ?int $timestamp = null, ?string $msisdn = null, bool $skipPhoneInput = false)
    {
        $this->orderNumber = $orderNumber;
        $this->amount = $amount;
        $this->timestamp = $timestamp ?? self::nowInMilliseconds();
        $this->msisdn = $msisdn;
        $this->skipPhoneInput = $skipPhoneInput;

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

        if (isset($payment['msisdn']) && ! (bool) (preg_match('/^509\d{8}$/', $payment['msisdn']))) {
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
     * fromArray - Deprecated, use from()
     *
     * @param  array<string, mixed>  $payment  Payment request array
     * @return PaymentRequest PaymentRequest object
     *
     * @deprecated Use PaymentRequest::from() instead
     */
    public static function fromArray(array $payment): \Mds\Natcash\PaymentRequest
    {
        @trigger_error('PaymentRequest::fromArray() is deprecated, use PaymentRequest::from() instead.', E_USER_DEPRECATED);

        return self::from($payment);
    }

    /**
     * getOrderNumber - Get Order Number
     *
     * @return string Transaction ID (user to conciliate)
     *
     * @deprecated Use getOrderId() instead
     */
    public function getOrderNumber(): string
    {
        @trigger_error('getOrderNumber() is deprecated, use getOrderId() instead.', E_USER_DEPRECATED);
        return $this->orderNumber;
    }

    /**
     * getOrderId - Get Order ID (standardized accessor, alias for getOrderNumber)
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
