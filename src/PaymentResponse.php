<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Core\ResponseStatus;
use Mds\Natcash\Exception\NatcashException;

final class PaymentResponse
{
    /**
     * url - Payment URL
     *
     * @var string Payment URL
     */
    private $url;

    /**
     * expiredAt - Expiration Time
     *
     * @var int Expiration Duration (in seconds)
     */
    private $expiredAt;

    public function __construct(string $url, int $expiredAt)
    {
        $this->url = $url;
        $this->expiredAt = $expiredAt;
    }

    /**
     * fromResponse - Create PaymentResponse Object from Response
     *
     * @param  \Psr\Http\Message\ResponseInterface  $res  Response from Moncash
     * @return PaymentResponse PaymentResponse Object
     */
    public static function fromResponse(\Psr\Http\Message\ResponseInterface $res)
    {
        $respons = ResponseStatus::fromResponse($res);

        if (! $respons->isSuccess()) {
            throw new NatcashException($respons->getMessage());
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
     * getExpiredAt - Get Expiration Time
     *
     * @return int Expiration Duration (in seconds)
     */
    public function getExpiredAt(): int
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
