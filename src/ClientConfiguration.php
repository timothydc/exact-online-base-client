<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient;

class ClientConfiguration
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $webhookSecret;

    protected string $redirectUrl;
    protected ?string $baseUrl;

    protected ?string $division;
    protected ?string $language;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $webhookSecret,
        string $redirectUrl,
        ?string $baseUrl = null,
        ?string $division = null,
        ?string $language = null
    )
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setWebhookSecret($webhookSecret);
        $this->setBaseUrl($baseUrl);
        $this->setRedirectUrl($redirectUrl);
        $this->setDivision($division);
        $this->setLanguage($language);
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function getWebhookSecret(): string
    {
        return $this->webhookSecret;
    }

    public function setWebhookSecret(string $webhookSecret): void
    {
        $this->webhookSecret = $webhookSecret;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(?string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(?string $division): void
    {
        $this->division = $division;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }
}
