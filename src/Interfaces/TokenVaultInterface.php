<?php

declare(strict_types=1);

namespace TimothyDC\ExactOnline\BaseClient\Interfaces;

interface TokenVaultInterface
{
    public function makeToken(?string $accessToken, ?string $refreshToken, int $expiresAt): AccessTokenInterface;

    public function store(AccessTokenInterface $accessToken): void;

    public function retrieve(): AccessTokenInterface;

    public function remove(string $accessToken): void;

    public function clear(): void;
}
