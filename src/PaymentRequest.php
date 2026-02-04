<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Exception\NatcashException;

final class PaymentRequest
{
    /**
     * orderNumber - Order Number
     *
     * @var string Transaction ID (user to conciliate)
     */
    private $orderNumber;

    /**
     * amount - Amount
     *
     * @var float Total transaction amount.
     */
    private $amount;

    /**
     * msisdn - MSISDN
     *
     * @var ?string User phone number (Ex: 509XXXXXXXXX)
     */
    private $msisdn;

    /**
     * skipPhoneInput - Skip Phone Input
     *
     * @var bool Skip the phone input on gateway
     */
    private $skipPhoneInput = false;

    /**
     * timestamp - Timestamp
     *
     * @var int Transaction creation time. (milisecond)
     */
    private $timestamp;

    /**
     * requestId - Request ID
     *
     * @var string Request ID
     */
    private $requestId = '';

    public function __construct(string $orderNumber, float $amount, ?int $timestamp = null, ?string $msisdn = null, bool $skipPhoneInput = false)
    {
        $this->orderNumber = $orderNumber;
        $this->amount = $amount;
        $this->timestamp = $timestamp ?? self::nowInMilliseconds();
        $this->msisdn = $msisdn;
        $this->skipPhoneInput = $skipPhoneInput;

        if ($this->getSkipPhoneInput() && (is_null($this->getMsisdn()) || empty($this->getMsisdn()))) {
            throw new NatcashException('MSISDN is required when skipPhoneInput is true');
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
     * fromArray - Create a new PaymentRequest instance from Array
     *
     * @param  array<string, mixed>  $payment  Payment Request Array
     * @return PaymentRequest PaymentRequest Object
     *
     * @throws NatcashException
     */
    public static function fromArray(array $payment)
    {
        if (! isset($payment['orderNumber']) || empty($payment['orderNumber'])) {
            throw new NatcashException('Missing `orderNumber` in payment request array');
        }

        if (! isset($payment['amount']) || empty($payment['amount'])) {
            throw new NatcashException('Missing `amount` in payment request array');
        }

        if (! is_numeric($payment['amount']) || $payment['amount'] <= 0) {
            throw new NatcashException('Invalid `amount` in payment request array');
        }

        if (! isset($payment['timestamp']) || empty($payment['timestamp'])) {
            $payment['timestamp'] = self::nowInMilliseconds();
        }

        if (isset($payment['msisdn']) && ! (bool) (preg_match('/^509\d{8}$/', $payment['msisdn']))) {
            throw new NatcashException('Invalid `msisdn` in payment request array');
        }

        if (isset($payment['skipPhoneInput']) && filter_var($payment['skipPhoneInput'], FILTER_VALIDATE_BOOLEAN) === false) {
            throw new NatcashException('Invalid `skipPhoneInput` in payment request array');
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
     * getOrderNumber - Get Order Number
     *
     * @return string Transaction ID (user to conciliate)
     */
    public function getOrderNumber(): string
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
     * @return array<string, mixed> Payment as Array
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
