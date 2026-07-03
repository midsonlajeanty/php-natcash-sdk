<?php

declare(strict_types=1);

namespace Mds\Natcash\Core;

use Psr\Http\Message\ResponseInterface;

final class ResponseStatus
{
    public const MSG_SUCCESS = 0;

    public const ERR_COMMON = 1;

    public const ERR_TRANSACTION_EXPIRED = 2;

    public const ERR_PARAMETERS_INVALID = 3;

    public const ERR_MISSING_PARAMETERS = 4;

    public const ERR_MERCHANT_NOT_FOUND = 100;

    public const ERR_TRANSACTION_NOT_FOUND = 101;

    public const ERR_DUPLICATE_REQUEST_ID = 102;

    /**
     * @param  int  $status  The numeric status code
     * @param  string  $code  The status code
     * @param  string  $message  The descriptive message
     * @param  object  $data  Response data
     */
    public function __construct(
        /**
         * status - The numeric status code
         */
        public int $status,
        /**
         * code - The status code
         */
        public string $code,
        /**
         * message - The descriptive message
         */
        public string $message,
        /**
         * data - Response  data
         */
        private readonly object $data
    ) {}

    /**
     * fromResponse - Create ResponseStatus Object from Response
     *
     * @param  ResponseInterface  $res  Response from Moncash
     * @return ResponseStatus ResponseStatus Object
     */
    public static function fromResponse(ResponseInterface $res): self
    {
        $data = json_decode($res->getBody()->getContents());

        return new self(
            $data->status,
            $data->code,
            $data->message,
            (object) $data
        );
    }

    /**
     * Helper to check if the status is a success.
     */
    public function isSuccess(): bool
    {
        return $this->status === self::MSG_SUCCESS;
    }

    /**
     * Get the description for a specific code.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the additional response data.
     *
     * @return object Response data
     */
    public function getData(): object
    {
        return $this->data;
    }

    /**
     * Convert the object to an array for JSON responses.
     *
     * @return array{status: int, code: string, message: string, data: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
            'data' => (array) $this->data,
        ];
    }
}
