<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Exceptions;

use Exception;
use Throwable;

class AuthenticationException extends Exception
{
    public const CODE = 401;
    public const INVALID_REFRESH_TOKEN = 'Invalid refresh token. Re-authentication required.';

    protected ?string $clientId = null;

    public function __construct($message = '', $code = 0, ?Throwable $previous = null, ?string $clientId = null)
    {
        parent::__construct($message, $code, $previous);

        $this->clientId = $clientId;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }
}
