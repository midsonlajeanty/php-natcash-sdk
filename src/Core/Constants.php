<?php

declare(strict_types=1);

namespace Mds\Natcash\Core;

/**
 * Constants
 */
final class Constants
{
    public const LIVE_URL = 'https://merchantpay.natcom.com.ht/api/online-payment/';

    public const SANDBOX_URL = 'https://testmerchantpay.natcom.com.ht/api/online-payment/';

    public const PAYMENT_URI = 'credential';

    public const TRANSACTION_DETAILS_URI = 'merchant/checkTransaction';

    public const DEFAULT_TIMEOUT = 10;

    public const DEFAULT_CONNECT_TIMEOUT = 10;

    /** @deprecated Use DEFAULT_TIMEOUT. */
    public const REQUEST_TIMEOUT = self::DEFAULT_TIMEOUT;

    // seconds
    public const ACCESS_KEY_ALGORITHM = 'sha256';

    public const SUPPORTED_LANGUAGES = ['ht', 'fr', 'en'];
}
