<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Exceptions;

use Picqer\Financials\Exact\ApiException;
use Throwable;

class RateLimitException extends ApiException
{
    public const CODE = 429;

    protected ?int $resetTimestamp = null;
    protected ?string $clientId = null;

    public function __construct($message = "", $code = 0, Throwable $previous = null, int $resetTimestamp = null, string $clientId = null)
    {
        parent::__construct($message, $code, $previous);

        $this->resetTimestamp = $resetTimestamp;
        $this->clientId = $clientId;
    }

    public function getResetTimestamp(): ?int
    {
        return $this->resetTimestamp;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }
}