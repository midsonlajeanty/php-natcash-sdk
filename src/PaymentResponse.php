<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Core\ResponseStatus;
use Mds\Natcash\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

final readonly class PaymentResponse
{
    public function __construct(
        /**
         * url - Payment URL
         *
         * @var string Payment URL
         */
        private string $url,
        /**
         * expiredAt - Expiration Time
         *
         * @var int Expiration Duration (in seconds)
         */
        private int $expiredAt
    ) {}

    /**
     * fromResponse - Create PaymentResponse Object from Response
     *
     * @param  ResponseInterface  $res  Response from Moncash
     * @return PaymentResponse PaymentResponse Object
     */
    public static function fromResponse(ResponseInterface $res): self
    {
        $respons = ResponseStatus::fromResponse($res);

        if (! $respons->isSuccess()) {
            throw new ApiException($respons->getMessage());
        }

        $body = $respons->getData();

        return new self($body->url, $body->expiredAt);
    }

    /**
     * getUrl - Get Payment URL
     *
     * @return string Payment URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * getExpiresAt - Get Expiration Time
     *
     * @return int Expiration Duration (in seconds)
     */
    public function getExpiresAt(): int
    {
        return $this->expiredAt;
    }

    /**
     * getRedirect - Get Payment Redirect URL
     *
     * @return string Payment Redirect URL
     */
    public function getRedirect(): string
    {
        return $this->getUrl();
    }
}
