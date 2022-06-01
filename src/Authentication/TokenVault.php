<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Authentication;

use JsonException;
use PolarisDC\ExactOnline\BaseClient\Interfaces\AccessTokenInterface;
use PolarisDC\ExactOnline\BaseClient\Interfaces\TokenVaultInterface;
use Symfony\Component\Filesystem\Filesystem;

class TokenVault implements TokenVaultInterface
{
    protected string $storagePath;

    public function __construct(?string $storagePath = null)
    {
        if ($storagePath) {
            $this->setStoragePath($storagePath);
        }
    }

    public function makeToken(?string $accesToken, ?string $refreshToken, int $expiresAt): AccessTokenInterface
    {
        return new AccessToken($accesToken, $refreshToken, $expiresAt);
    }

    /**
     * @throws JsonException
     */
    public function store(AccessTokenInterface $accessToken): void
    {
        (new Filesystem())
            ->dumpFile($this->storagePath, json_encode([
                'accessToken' => $accessToken->getAccessToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expiresAt' => $accessToken->getExpiresAt(),
            ], \JSON_THROW_ON_ERROR));
    }

    public function retrieve(): AccessTokenInterface
    {
        if (! (new Filesystem())->exists($this->storagePath)) {
            return $this->makeToken(null, null, 0);
        }

        try {
            $json = json_decode(file_get_contents($this->storagePath), true, 512, \JSON_THROW_ON_ERROR);

            if (! $json) {
                throw new JsonException('No tokens found.');
            }
        } catch (JsonException $e) {
            return $this->makeToken(null, null, 0);
        }

        return $this->makeToken($json['accessToken'], $json['refreshToken'], $json['expiresAt']);
    }

    public function clear(): void
    {
        if ((new Filesystem())->exists($this->storagePath)) {
            unlink($this->storagePath);
        }
    }

    public function setStoragePath(string $storagePath): self
    {
        $this->storagePath = $storagePath;

        return $this;
    }

    public function remove(string $accessToken): void
    {
    }
}
