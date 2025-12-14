<?php

declare(strict_types=1);

namespace Timothydc\ExactOnline\BaseClient;

class ClientConfiguration
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $webhookSecret;
    protected string $redirectUrl;
    protected ?string $baseUrl;
    protected ?string $division;
    protected ?string $language;
    protected mixed $country;
    protected array $extensions = [];

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $webhookSecret,
        string $redirectUrl,
        ?string $baseUrl = null,
        ?string $division = null,
        ?string $language = null,
        mixed $country = null,
        array $extensions = []
    ) {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setWebhookSecret($webhookSecret);
        $this->setBaseUrl($baseUrl);
        $this->setRedirectUrl($redirectUrl);
        $this->setDivision($division);
        $this->setLanguage($language);
        $this->setCountry($country);
        $this->setExtensions($extensions);
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function getWebhookSecret(): string
    {
        return $this->webhookSecret;
    }

    public function setWebhookSecret(string $webhookSecret): self
    {
        $this->webhookSecret = $webhookSecret;

        return $this;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(?string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(string $redirectUrl): self
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(?string $division): self
    {
        $this->division = $division;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getCountry(): mixed
    {
        return $this->country;
    }

    public function setCountry(mixed $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getExtension(string $key): mixed
    {
        return $this->extensions[$key] ?? null;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(array $extensions): void
    {
        $this->extensions = $extensions;
    }

    public function addExtensions(array $extensions): void
    {
        foreach ($extensions as $key => $extension) {
            $this->addExtension($key, $extension);
        }
    }

    public function addExtension(string $key, mixed $extension): self
    {
        $this->extensions[$key] = $extension;

        return $this;
    }

    public function hasExtension(string $key): bool
    {
        return isset($this->extensions[$key]);
    }

    public function removeExtension(string $key): void
    {
        if (isset($this->extensions[$key])) {
            unset($this->extensions[$key]);
        }
    }
}
