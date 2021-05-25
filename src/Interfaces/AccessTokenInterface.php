<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Interfaces;

interface AccessTokenInterface
{
    public function getAccessToken(): ?string;

    public function getRefreshToken(): ?string;

    public function getExpiresAt(): int;
}
