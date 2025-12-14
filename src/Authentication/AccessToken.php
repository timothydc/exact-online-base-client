<?php

declare(strict_types=1);

namespace TimothyDC\ExactOnline\BaseClient\Authentication;

use TimothyDC\ExactOnline\BaseClient\Interfaces\AccessTokenInterface;

class AccessToken implements AccessTokenInterface
{
    protected ?string $accessToken;
    protected ?string $refreshToken;
    protected int $expiresAt;

    public function __construct(?string $accessToken = null, ?string $refreshToken = null, int $expiresAt = 0)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresAt = $expiresAt;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }
}
