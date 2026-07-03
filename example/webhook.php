<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

require __DIR__.'/constant.php';

use Mds\Natcash\Config;
use Mds\Natcash\Exception\NatcashException;
use Mds\Natcash\Natcash;

if (isset($_GET['orderNumber'])) {

    $code = (int) $_GET['code'];
    $orderNumber = $_GET['orderNumber'];
    $signature = $_GET['signature'];

    $config = new Config(PRIVATE_KEY, PARTNER_CODE, FUNCTION_CODE, USERNAME, PASSWORD, CALLBACK_URL);

    // Create Natcash Instance
    $natcash = new Natcash($config, DEBUG);

    try {

        // Verify Webhook Payload Signature
        $isValid = $natcash->verifyPayloadSignature($orderNumber, $code, $signature);

        if ($isValid) {
            // Retrieve transaction details for the given order
            $details = $natcash->getTransactionDetailsByOrderId($orderNumber);
        }
    } catch (NatcashException $e) {
        $message = 'Natcash Exception: '.$e->getMessage().PHP_EOL;
    } catch (Exception $e) {
        $message = 'General Exception: '.$e->getMessage().PHP_EOL;
    }
} else {
    // redirect to home
    header('Location: /');
    http_response_code(302);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Natcash SDK - Demo</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
        crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="container px-3 my-5 clearfix">
        <!-- Shopping cart table -->
        <?php if (($isValid ?? false) && isset($details)) { ?>
            <div class="alert alert-success">
                Transaction Success
            </div>
        <?php } else { ?>
            <div class="alert alert-danger">
                Transaction Failed : <?= $message ?? 'Invalid Signature' ?>
            </div>
        <?php } ?>

        <?php if (isset($details)) { ?>
            <div class="card">
                <div class="card-header">
                    <h2>Transaction Details</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0">
                            <tbody>
                                <tr>
                                    <td class="text-center align-middle p-4">
                                        <span>Transaction ID : </span>
                                        <span><?= $details->getTransactionId() ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center align-middle p-4">
                                        <span>Order ID : </span>
                                        <span><?= $details->getOrderId() ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center align-middle p-4">
                                        <span>Amount : </span>
                                        <span><?= $details->getAmount() ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center align-middle p-4">
                                        <span>Payer : </span>
                                        <span><?= $details->getPayer() ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center align-middle p-4 <?= $details->isSuccessful() ? 'text-success' : 'text-danger' ?>">
                                        <span>Status : </span>
                                        <span><?= $details->isSuccessful() ? 'Successful' : 'Failed' ?></span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous">
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD"
        crossorigin="anonymous">
    </script>

</body>

</html>
