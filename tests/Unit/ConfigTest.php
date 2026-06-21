<?php

declare(strict_types=1);

use Mds\Natcash\Config;
use Mds\Natcash\Exception\InvalidConfigException;

test('config object creation', function (): void {
    $config = new Config(
        'privateKey',
        'partnerCode',
        'functionCode',
        'username',
        'password',
        'https://example.com'
    );

    expect($config->getPrivateKey())->toBe('privateKey');
    expect($config->getPartnerCode())->toBe('partnerCode');
    expect($config->getFunctionCode())->toBe('functionCode');
    expect($config->getUsername())->toBe('username');
    expect($config->getPassword())->toBe('password');
    expect($config->getCallbackUrl())->toBe('https://example.com');
    expect($config->getEnableFee())->toBeTrue();
    expect($config->getLanguage())->toBe('ht');
});

test('config from array', function (): void {
    $configArray = [
        'privateKey' => 'privateKey',
        'partnerCode' => 'partnerCode',
        'functionCode' => 'functionCode',
        'username' => 'username',
        'password' => 'password',
        'callbackUrl' => 'https://example.com',
        'enableFee' => true,
        'language' => 'en',
    ];

    $config = Config::fromArray($configArray);

    expect($config->getPrivateKey())->toBe('privateKey');
    expect($config->getPartnerCode())->toBe('partnerCode');
    expect($config->getFunctionCode())->toBe('functionCode');
    expect($config->getUsername())->toBe('username');
    expect($config->getPassword())->toBe('password');
    expect($config->getCallbackUrl())->toBe('https://example.com');
    expect($config->getEnableFee())->toBeTrue();
    expect($config->getLanguage())->toBe('en');
});

test('config from array missing key throws exception', function (): void {
    $configArray = [
        'partnerCode' => 'partnerCode',
    ];

    Config::fromArray($configArray);
})->throws(InvalidConfigException::class);

test('config from array invalid callback url throws exception', function (): void {
    $configArray = [
        'privateKey' => 'privateKey',
        'partnerCode' => 'partnerCode',
        'functionCode' => 'functionCode',
        'username' => 'username',
        'password' => 'password',
        'callbackUrl' => 'invalid-url',
    ];

    Config::fromArray($configArray);
})->throws(InvalidConfigException::class);
