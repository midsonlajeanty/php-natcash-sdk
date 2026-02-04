<p align="center">
    <img src="https://warehouse.canal-overseas.com/content/0001/44/0ac56c893f8fc943f6b3e55f236a0697a76ef750.png" width="200" alt="Natcash Logo">
</p>

<p align="center">
    <a href="https://github.com/midsonlajeanty/php-natcash-sdk/actions">
        <img src="https://github.com/midsonlajeanty/php-natcash-sdk/actions/workflows/tests%20.yml/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/midsonlajeanty/php-natcash-sdk">
        <img src="https://img.shields.io/packagist/dt/midsonlajeanty/php-natcash-sdk" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/midsonlajeanty/php-natcash-sdk">
        <img src="https://img.shields.io/packagist/v/midsonlajeanty/php-natcash-sdk" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/midsonlajeanty/php-natcash-sdk">
        <img src="https://img.shields.io/packagist/l/midsonlajeanty/php-natcash-sdk" alt="License">
    </a>
</p>


Minimum SDK to process payment with Natcom Natcash Payment Gateway

## Features

- Create Payment Transaction and get gateway URL  (Natcash Checkout)
- Get Transaction Details by Order ID

## Getting started

```
composer require midsonlajeanty/php-natcash-sdk 
```

## Usage

### Init Payment and get Payment URL  (Natcash Checkout)

```php
use Mds\Natcash\Config;
use Mds\Natcash\Natcash;
use Mds\Natcash\PaymentRequest;

// Natcash Merchant Credentials
$configArray = [
    'privateKey' => PRIVATE_KEY,
    'partnerCode' => PARTNER_CODE,
    'functionCode' => FUNCTION_CODE,
    'username' => USERNAME,
    'password' => PASSWORD,
    'callbackUrl' => CALLBACK_URL,
];

$config = Config::fromArray($configArray);

// Payment Request
$paymentArray = [
    'orderNumber' => 'ORDER-001',
    'amount' => 10,
];
$payment = PaymentRequest::fromArray($paymentArray);

// Init SDK with config
$natcash = new Natcash($config, DEBUG);

// Make Payment with payment request and Amount
$response = $natcash->makePayment($payment);

// Get Payment URL  (Natcash Checkout)
$response->getRedirect();
```

### Get Transaction Details by Order ID

```php
use Mds\Natcash\Config;
use Mds\Natcash\Natcash;
use Mds\Natcash\PaymentRequest;

// Natcash Merchant Credentials
$configArray = [
    'privateKey' => PRIVATE_KEY,
    'partnerCode' => PARTNER_CODE,
    'functionCode' => FUNCTION_CODE,
    'username' => USERNAME,
    'password' => PASSWORD,
    'callbackUrl' => CALLBACK_URL,
];

$config = Config::fromArray($configArray);

// Init SDK with config
$natcash = new Natcash($config, DEBUG);

// Verify Webhook Payload Signature
$isValid = $natcash->verifyPayloadSignature('WEBHOOK_ORDER_NUMBER', 'WEBHOOK_CODE', 'WEBHOOK_SIGNATURE');

if ($isValid) {
    print_r("Signature is valid." . PHP_EOL . PHP_EOL);

    // Get Payment Details with OrderId provided by your app.
    $details = $natcash->getTransactionDetailsByOrderId($orderNumber);

} else {
    print_r("Signature is invalid." . PHP_EOL);
}
```

## Contributing

You have a lot of options to contribute to this project ! You can :

- [Fork](https://github.com/midsonlajeanty/php-natcash-sdk) on Github
- [Submit](https://github.com/midsonlajeanty/php-natcash-sdk/issues) a bug report.
- [Donate](https://www.buymeacoffee.com/midsonlajeanty) to the Developper
