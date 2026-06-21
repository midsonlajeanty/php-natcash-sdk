<p align="center">
    <img src="https://testmerchantpay.natcom.com.ht/merchant/_next/static/images/logo-1689408a84fe0c46831e7ae2d19fe04c.png" width="200" alt="Natcash Logo">
</p>

<p align="center">
    <a href="https://github.com/midsonlajeanty/php-natcash-sdk/actions">
        <img src="https://github.com/midsonlajeanty/php-natcash-sdk/actions/workflows/tests.yml/badge.svg" alt="Build Status">
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
$config = new Config(PRIVATE_KEY, PARTNER_CODE, FUNCTION_CODE, USERNAME, PASSWORD, CALLBACK_URL);

// Payment Request
$payment = new PaymentRequest('ORDER-001', 10);

// Init SDK with config
$natcash = new Natcash($config, DEBUG);

// Make Payment with payment request
$response = $natcash->makePayment($payment);

// Get Payment URL (Natcash Checkout)
$response->getRedirect();
```

### Get Transaction Details by Order ID

```php
use Mds\Natcash\Config;
use Mds\Natcash\Natcash;

// Natcash Merchant Credentials
$config = new Config(PRIVATE_KEY, PARTNER_CODE, FUNCTION_CODE, USERNAME, PASSWORD, CALLBACK_URL);

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

## Common conventions (MonCash & NatCash)

The MonCash and NatCash SDKs share the same pattern. If you know one, you know the other:

| Step | Class / method |
|---|---|
| Configuration | `Config::from([...])` |
| Instantiation | `new <Gateway>($config, $debug = true)` |
| Request | `PaymentRequest::from([...])` |
| Payment | `makePayment(PaymentRequest): PaymentResponse` |
| Redirect | `$response->getRedirect()` |
| Details | `getTransactionDetailsByOrderId($orderId): TransactionDetails` |
| Result | `$details->getOrderId()`, `getTransactionId()`, `getAmount()`, `getPayer()`, `isSuccessful()` |

NatCash-specific features: HMAC signatures (`verifyPayloadSignature()`), `getMsisdn()`, `skipPhoneInput`.

## Contributing

You have a lot of options to contribute to this project ! You can :

- [Fork](https://github.com/midsonlajeanty/php-natcash-sdk) on Github
- [Submit](https://github.com/midsonlajeanty/php-natcash-sdk/issues) a bug report.
- [Donate](https://www.buymeacoffee.com/midsonlajeanty) to the Developper
