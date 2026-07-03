<?php

declare(strict_types=1);

namespace Mds\Natcash\Core;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Mds\Natcash\Config;
use Mds\Natcash\PaymentRequest;

/**
 * Core
 */
abstract class Core
{
    /**
     * config - Config
     *
     * @var Config Natcash Config Object
     */
    private Config $config;

    /**
     * client - API Client
     *
     * @var ClientInterface - Guzzle Client
     */
    private ClientInterface $client;

    /**
     * __construct - Create Core Instance
     *
     * @param  Config  $config  Natcash Config Object
     * @param  bool  $debug  Debug Mode
     */
    public function __construct(Config $config, $debug = true)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $debug ? Constants::SANDBOX_URL : Constants::LIVE_URL,
            'timeout' => Constants::REQUEST_TIMEOUT,
        ]);
    }

    /**
     * getClient - Get Guzzle Client
     *
     * @return ClientInterface Guzzle Client
     */
    final public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * setClient - Set Guzzle Client
     *
     * @param  ClientInterface  $client  Guzzle Client
     */
    final public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * getPaymentDataArray - Get Payment Data Array
     *
     * @param  PaymentRequest  $paymentRequest  Payment Request Object
     * @return array<string, mixed> Payment Data as Array
     */
    protected function getPaymentDataArray(PaymentRequest $paymentRequest): array
    {
        $paymentRequest->setRequestId($this->generateRequestId());

        $additionalData = [
            'signature' => $this->generatePaymentSignature($paymentRequest),
        ];

        return array_merge(
            $this->config->toArray(),
            $paymentRequest->toArray(),
            $additionalData
        );
    }

    /**
     * getTransactionDataArray - Get Transaction Data Array
     *
     * @param  string  $orderNumber  Order Number
     * @param  string|null  $requestId  Request ID
     * @return array<string, mixed> Transaction Data as Array
     */
    protected function getTransactionDataArray(string $orderNumber, ?string $requestId = null): array
    {
        $requestId = $requestId ?: $this->generateRequestId();

        return [
            'requestId' => $requestId,
            'username' => $this->config->getUsername(),
            'password' => $this->config->getPassword(),
            'partnerCode' => $this->config->getPartnerCode(),
            'orderNumber' => $orderNumber,
            'signature' => $this->generateTransactionSignature($orderNumber, $requestId),
        ];
    }

    /**
     * getTransactionDataArray - Get Transaction Data Array
     *
     * @param  string  $orderNumber  Order Number
     * @param  int  $code  Response Code { 1: Success, -3: Unknown , -1: Failed }
     * @param  string  $signature  Signature to validate
     * @return bool Signature Validity
     */
    protected function isPayloadSignatureValid(string $orderNumber, int $code, string $signature): bool
    {
        $generatedSignature = $this->generatePayloadValidationSignature($orderNumber, $code);

        return hash_equals($generatedSignature, $signature);
    }

    /**
     * generateAccessKey - Generate SHA256 Access Key from the Private Key and Request ID
     *
     * * @param  string $requestId Request ID
     */
    private function generateAccessKey(string $requestId): string
    {
        return hash(Constants::ACCESS_KEY_ALGORITHM, $this->config->getPrivateKey().$requestId);
    }

    /**
     * generatePayloadValidationAccessKey - Generate SHA256 Access Key for the payload validation
     *
     * * @param  string $orderNumber Order Number
     */
    private function generatePayloadValidationAccessKey(string $orderNumber): string
    {
        return hash(Constants::ACCESS_KEY_ALGORITHM, $this->config->getFunctionCode().$orderNumber);
    }

    /**
     * generateRequestId - Generate a UUID v4
     *
     * @return string UUID v4
     */
    private function generateRequestId(): string
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);

        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', mb_str_split(bin2hex($data), 4));
    }

    /**
     * generatePaymentSignature - Generate HMAC SHA256 Signature
     *
     * @param  PaymentRequest  $payment  Payment Request Object
     * @return string Signature
     */
    private function generatePaymentSignature(PaymentRequest $payment): string
    {
        $accessKey = $this->generateAccessKey($payment->getRequestId());

        $params = [
            'accessKey' => $accessKey,
            'partnerCode' => $this->config->getPartnerCode(),
            'username' => $this->config->getUsername(),
            'password' => $this->config->getPassword(),
            'timestamp' => $payment->getTimestamp(),
            'requestId' => $payment->getRequestId(),
            'orderNumber' => $payment->getOrderId(),
            'amount' => $payment->getAmount(),
        ];

        return hash_hmac(Constants::ACCESS_KEY_ALGORITHM, implode('', $params), $this->config->getPrivateKey());
    }

    /**
     * generateTransactionSignature - Generate HMAC SHA256 Signature
     *
     * @param  string  $orderNumber  Order Number
     * @param  string  $requestId  Request ID
     * @return string Signature
     */
    private function generateTransactionSignature(string $orderNumber, string $requestId): string
    {
        $accessKey = $this->generateAccessKey($requestId);

        $params = [
            'accessKey' => $accessKey,
            'partnerCode' => $this->config->getPartnerCode(),
            'username' => $this->config->getUsername(),
            'password' => $this->config->getPassword(),
            'orderNumber' => $orderNumber,
            'requestId' => $requestId,
        ];

        return hash_hmac(Constants::ACCESS_KEY_ALGORITHM, implode('', $params), $this->config->getPrivateKey());
    }

    /**
     * generatePayloadValidationSignature - Generate HMAC SHA256 Signature
     *
     * @param  string  $orderNumber  Order Number
     * @param  int  $code  Code
     * @return string Signature
     */
    private function generatePayloadValidationSignature(string $orderNumber, int $code): string
    {
        $accessKey = $this->generatePayloadValidationAccessKey($orderNumber);

        $params = [
            'accessKey' => $accessKey,
            'orderNumber' => $orderNumber,
            'code' => $code,
        ];

        return hash_hmac(Constants::ACCESS_KEY_ALGORITHM, implode('', $params), $this->config->getFunctionCode());
    }
}
