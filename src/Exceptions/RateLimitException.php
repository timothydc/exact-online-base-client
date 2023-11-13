<?php

declare(strict_types=1);

namespace TimothyDC\ExactOnline\BaseClient\Exceptions;

use Picqer\Financials\Exact\ApiException;
use Throwable;
use TimothyDC\ExactOnline\BaseClient\RateLimits;

class RateLimitException extends ApiException
{
    public const CODE = 429;

    protected ?RateLimits $rateLimits = null;
    protected ?string $clientId = null;

    public function __construct($message = '', $code = 0, ?Throwable $previous = null, ?RateLimits $rateLimits = null, ?string $clientId = null)
    {
        parent::__construct($message, $code, $previous);

        $this->rateLimits = $rateLimits;
        $this->clientId = $clientId;
    }

    public function getRateLimits(): RateLimits
    {
        return $this->rateLimits ?? new RateLimits();
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }
}
