<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Authentication;

interface TokenVaultInterface
{
    public function createToken(?string $accesToken, ?string $refreshToken, int $expiresAt): AccessTokenInterface;

    public function store(AccessTokenInterface $accessToken): void;

    public function retrieve(): AccessTokenInterface;

    public function clear(): void;
}
