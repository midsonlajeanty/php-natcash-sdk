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

Requires **PHP 8.2+**.

```
composer require midsonlajeanty/php-natcash-sdk
```

> **On PHP < 8.2?** Pin the 1.x line, which supports PHP 7.4+:
> ```
> composer require "midsonlajeanty/php-natcash-sdk:^1.0"
> ```

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

## Laravel

The package auto-registers a service provider and a facade (Laravel 9 to 13). Publish the config file:

```bash
php artisan vendor:publish --tag=natcash-config
```

Set your credentials in `.env` (they are loaded automatically):

```dotenv
NATCASH_PRIVATE_KEY=your-private-key
NATCASH_PARTNER_CODE=your-partner-code
NATCASH_FUNCTION_CODE=your-function-code
NATCASH_USERNAME=your-username
NATCASH_PASSWORD=your-password
NATCASH_CALLBACK_URL=https://your-app.test/natcash/callback
NATCASH_ENABLE_FEE=true
NATCASH_LANGUAGE=ht
# Sandbox gateway. Defaults to false (live); set true in local environments.
NATCASH_DEBUG=true
```

Then use the facade, or inject `NatcashInterface` (both resolve the same configured singleton):

```php
use Mds\Natcash\Laravel\Facades\Natcash;
use Mds\Natcash\PaymentRequest;

$response = Natcash::makePayment(new PaymentRequest('order-1', 250, null, 'msisdn'));

return redirect($response->getRedirect());
```

```php
use Mds\Natcash\NatcashInterface;

final class CheckoutController
{
    public function __construct(private NatcashInterface $natcash) {}
}
```

## Testing

The `Natcash` gateway is `final` (it is the only class that performs I/O), so it cannot be mocked directly. Instead, type-hint your application code against `NatcashInterface` and mock the interface:

```php
use Mds\Natcash\NatcashInterface;

final class CheckoutService
{
    public function __construct(private NatcashInterface $gateway) {}

    public function pay(PaymentRequest $request): PaymentResponse
    {
        return $this->gateway->makePayment($request);
    }
}

// Production: inject the real gateway.
new CheckoutService(new Natcash($config));
```

```php
// Test: mock the interface, no need to hit the network.
$gateway = Mockery::mock(NatcashInterface::class);
$gateway->shouldReceive('makePayment')->once()->andReturn($fakeResponse);

$service = new CheckoutService($gateway);
```

Value objects (`Config`, `PaymentRequest`, `PaymentResponse`, `TransactionDetails`) are also `final` — don't mock them, just construct them with test data.

## Contributing

You have a lot of options to contribute to this project ! You can :

- [Fork](https://github.com/midsonlajeanty/php-natcash-sdk) on Github
- [Submit](https://github.com/midsonlajeanty/php-natcash-sdk/issues) a bug report.
- [Donate](https://www.buymeacoffee.com/midsonlajeanty) to the Developper
