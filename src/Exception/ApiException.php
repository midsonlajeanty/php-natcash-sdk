<?php

declare(strict_types=1);

namespace Mds\Natcash\Exception;

/**
 * Thrown when the Natcash API returns an error (non-success response or HTTP error).
 */
class ApiException extends NatcashException {}
