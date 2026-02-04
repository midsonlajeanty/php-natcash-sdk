<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

require __DIR__ . '/constant.php';

use Mds\Natcash\Config;
use Mds\Natcash\Natcash;
use Mds\Natcash\PaymentRequest;

$configArray = [
    'privateKey' => PRIVATE_KEY,
    'partnerCode' => PARTNER_CODE,
    'functionCode' => FUNCTION_CODE,
    'username' => USERNAME,
    'password' => PASSWORD,
    'callbackUrl' => CALLBACK_URL,
];

$config = Config::fromArray($configArray);

$orderNumber = uniqid();

$signature = '<TRANSACTION_SIGNATURE_FROM_WEBHOOK>';
$code = '<RESPONSE_CODE_FROM_WEBHOOK>';

print_r("Order Number: $orderNumber" . PHP_EOL);

$paymentArray = [
    'orderNumber' => $orderNumber,
    'amount' => 10,
    'msisdn' => MSISDN,
];

$payment = PaymentRequest::fromArray($paymentArray);

$natcash = new Natcash($config, DEBUG);

try {

    // Make Payment with payment request
    $response = $natcash->makePayment($payment);

    print_r($response->getRedirect() . PHP_EOL . PHP_EOL);


    // Verify Webhook Payload Signature
    // $isValid = $natcash->verifyPayloadSignature($orderNumber, $code, $signature);

    // if ($isValid) {
    //     print_r("Signature is valid." . PHP_EOL . PHP_EOL);

    //     // Get Payment Details with OrderId provided by your app.
    //     $details = $natcash->getTransactionDetailsByOrderId($orderNumber);

    //     print_r(json_encode($details->toArray(), JSON_PRETTY_PRINT));
    // } else {
    //     print_r("Signature is invalid." . PHP_EOL);
    // }

} catch (Mds\Natcash\Exception\NatcashException $e) {
    echo 'Natcash Exception: ' . $e->getMessage() . PHP_EOL;
} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
}
