<?php

declare(strict_types=1);

namespace Mds\Natcash;

use Mds\Natcash\Core\Constants;
use Mds\Natcash\Exception\InvalidConfigException;

/**
 * Payment Configuration
 *
 * @final
 */
class Config
{
    /**
     * privateKey - Private Key
     *
     * @var string Private Key provided by Natcash
     */
    private string $privateKey;

    /**
     * partnerCode - Partner Code
     *
     * @var string Third-Party Partner Code
     */
    private string $partnerCode;

    /**
     * functionCode - Function Code
     *
     * @var string Third-Party Function Code
     */
    private string $functionCode;

    /**
     * username - Username
     *
     * @var string Third-Party Username
     */
    private string $username;

    /**
     * password - Password
     *
     * @var string Third-Party Password
     */
    private string $password;

    /**
     * callbackUrl - Callback URL
     *
     * @var string Third-Party Callback URL
     */
    private string $callbackUrl;

    /**
     * enableFee - Enable Fee
     *
     * @var bool Enable Fee
     */
    private bool $enableFee;

    /**
     * language - Language
     *
     * @var string Language (ht/fr/en)
     */
    private string $language;

    /**
     * __construct - Create a new Config instance
     *
     * @param  string  $privateKey  Private Key provided by Natcash
     * @param  string  $partnerCode  Third-Party Partner Code
     * @param  string  $functionCode  Third-Party Function Code
     * @param  string  $username  Third-Party Username
     * @param  string  $password  Third-Party Password
     * @param  string  $callbackUrl  Third-Party Callback URL
     */
    public function __construct(
        string $privateKey,
        string $partnerCode,
        string $functionCode,
        string $username,
        string $password,
        string $callbackUrl,
        bool $enableFee = true,
        string $language = 'ht'
    ) {
        $this->privateKey = $privateKey;
        $this->partnerCode = $partnerCode;
        $this->functionCode = $functionCode;
        $this->username = $username;
        $this->password = $password;
        $this->callbackUrl = $callbackUrl;
        $this->enableFee = $enableFee;
        $this->language = $language;
    }

    /**
     * from - Create a new Config instance from a configuration array
     *
     * @param  array<string, mixed>  $config  Natcash configuration array
     * @return Config Natcash config object
     *
     * @throws InvalidConfigException
     */
    public static function from(array $config): self
    {
        if (! isset($config['privateKey']) || empty($config['privateKey'])) {
            throw new InvalidConfigException('Missing `privateKey` in configuration array');
        }

        if (! isset($config['partnerCode']) || empty($config['partnerCode'])) {
            throw new InvalidConfigException('Missing `partnerCode` in configuration array');
        }

        if (! isset($config['functionCode']) || empty($config['functionCode'])) {
            throw new InvalidConfigException('Missing `functionCode` in configuration array');
        }

        if (! isset($config['username']) || empty($config['username'])) {
            throw new InvalidConfigException('Missing `username` in configuration array');
        }

        if (! isset($config['password']) || empty($config['password'])) {
            throw new InvalidConfigException('Missing `password` in configuration array');
        }

        if (! isset($config['callbackUrl']) || empty($config['callbackUrl'])) {
            throw new InvalidConfigException('Missing `callbackUrl` in configuration array');
        }

        if (! filter_var($config['callbackUrl'], FILTER_VALIDATE_URL)) {
            throw new InvalidConfigException('Invalid `callbackUrl` in configuration array');
        }

        if (! isset($config['enableFee'])) {
            $config['enableFee'] = true;
        }

        if (! filter_var($config['enableFee'], FILTER_VALIDATE_BOOLEAN) && ! is_bool($config['enableFee'])) {
            throw new InvalidConfigException('Invalid `enableFee` in configuration array');
        }

        if (! isset($config['language'])) {
            $config['language'] = 'ht';
        }

        if (! in_array($config['language'], Constants::SUPPORTED_LANGUAGES)) {
            throw new InvalidConfigException('Invalid `language` in configuration array');
        }

        return new self(
            $config['privateKey'],
            $config['partnerCode'],
            $config['functionCode'],
            $config['username'],
            $config['password'],
            $config['callbackUrl'],
            $config['enableFee'],
            $config['language']
        );
    }

    /**
     * fromArray - Deprecated, use from()
     *
     * @param  array<string, mixed>  $config  Natcash configuration array
     * @return Config Natcash config object
     *
     * @deprecated Use Config::from() instead
     */
    public static function fromArray(array $config): \Mds\Natcash\Config
    {
        @trigger_error('Config::fromArray() is deprecated, use Config::from() instead.', E_USER_DEPRECATED);

        return self::from($config);
    }

    /**
     * getPrivateKey - Get Private Key
     *
     * @return string Private Key provided by Natcash
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * getPartnerCode - Get Partner Code
     *
     * @return string Third-Party Partner Code
     */
    public function getPartnerCode(): string
    {
        return $this->partnerCode;
    }

    /**
     * getFunctionCode - Get Function Code
     *
     * @return string Third-Party Function Code
     */
    public function getFunctionCode(): string
    {
        return $this->functionCode;
    }

    /**
     * getUsername - Get Username
     *
     * @return string Third-Party Username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * getPassword - Get Password
     *
     * @return string Third-Party Password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * getCallbackUrl - Get Callback URL
     *
     * @return string Third-Party Callback URL
     */
    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    /**
     * getEnableFee - Get Enable Fee
     *
     * @return bool Natcash Enable Fee
     */
    public function getEnableFee(): bool
    {
        return $this->enableFee;
    }

    /**
     * getLanguage - Get Language
     *
     * @return string Natcash Language
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * toArray - Convert Config Object to Array
     *
     * @return array{partnerCode: string, username: string, password: string, callbackUrl: string, enableFee: bool, language: string} Config as array
     */
    public function toArray(): array
    {
        return [
            'partnerCode' => $this->partnerCode,
            'username' => $this->username,
            'password' => $this->password,
            'callbackUrl' => $this->callbackUrl,
            'enableFee' => $this->enableFee,
            'language' => $this->language,
        ];
    }
}
