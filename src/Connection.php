<?php
declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient;

use GuzzleHttp\Middleware;
use Picqer\Financials\Exact\Connection as PicqerConnection;
use PolarisDC\ExactOnline\BaseClient\Exceptions\TokenRefreshException;
use PolarisDC\ExactOnline\BaseClient\Interfaces\TokenVaultInterface;
use PolarisDC\ExactOnline\BaseClient\Traits\Lockable;
use PolarisDC\ExactOnline\BaseClient\Traits\Loggable;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;

class Connection extends PicqerConnection
{
    use Lockable;
    use Loggable;

    protected string $clientId;
    protected string $webhookSecret;
    protected string $redirectUrl;

    protected TokenVaultInterface $tokenVault;

    public function __construct(?ClientConfiguration $configuration = null, ?TokenVaultInterface $tokenVault = null)
    {
        $this->setLockKey('exact-online-connection-lock');

        if ($tokenVault) {
            $this->setTokenVault($tokenVault);
        }

        if ($configuration) {
            $this->applyConfiguration($configuration);
        }

        $this->setCallbacks();
    }

    public function applyConfiguration(ClientConfiguration $configuration): void
    {
        $this->setClientId($configuration->getClientId());
        $this->setExactClientSecret($configuration->getClientSecret());
        $this->setWebhookSecret($configuration->getWebhookSecret());
        $this->setRedirectUrl($configuration->getRedirectUrl());
        $this->setBaseUrl($configuration->getBaseUrl() ?: 'https://start.exactonline.be');

        if ($configuration->getDivision()) {
            $this->setDivision($configuration->getDivision());
        }

        if ($configuration->getLanguage()) {
            $this->setCustomDescriptionLanguage($configuration->getLanguage());
        }
    }

    protected function setCallbacks(): void
    {
        // use this to save the access token, refresh token and 'token expires time'
        $this->setTokenUpdateCallback([$this, 'updateAccessToken']);

        // use this to load access token, refresh tokens and 'token expires time' from storage into your connection
        $this->setRefreshAccessTokenCallback([$this, 'refreshAccessToken']);

        // use this to lock the connection to block other connections from requesting a new refresh token
        $this->setAcquireAccessTokenLockCallback([$this, 'acquireAccessTokenLock']);

        // use this to unlock the connection
        $this->setAcquireAccessTokenUnlockCallback([$this, 'releaseAccessTokenLock']);
    }

    public function isAuthorized(): bool
    {
        $accessToken = $this->tokenVault->retrieve();
        return ($accessToken->getAccessToken() && $accessToken->getRefreshToken() && $accessToken->getExpiresAt());
    }

    /**
     * Sets the language sensitive properties such as descriptions in a specific language.
     *
     * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItems#goodToKnow
     * @param string $language
     */
    public function setCustomDescriptionLanguage(string $language): void
    {
        $this->insertMiddleWare(Middleware::mapRequest(fn (RequestInterface $request) => $request->withHeader('CustomDescriptionLanguage', $language)));
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId($exactClientId): void
    {
        $this->setExactClientId($exactClientId);

        $this->clientId = $exactClientId;
    }

    public function getWebhookSecret(): string
    {
        return $this->webhookSecret;
    }

    public function setWebhookSecret(string $webhookSecret): void
    {
        $this->webhookSecret = $webhookSecret;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl($redirectUrl): void
    {
        parent::setRedirectUrl($redirectUrl);
        $this->redirectUrl = $redirectUrl;
    }

    public function setTokenVault(TokenVaultInterface $tokenVault): void
    {
        $this->tokenVault = $tokenVault;
    }

    public function loadTokensFromVault(): void
    {
        $accessToken = $this->tokenVault->retrieve();

        $this->setAccessToken($accessToken->getAccessToken());
        $this->setRefreshToken($accessToken->getRefreshToken());
        $this->setTokenExpires($accessToken->getExpiresAt());
    }

    public function reloadTokens(): void
    {
        $this->loadTokensFromVault();
    }

    /**
     * @param Connection $connection
     * @throws TokenRefreshException
     */
    public function acquireAccessTokenLock(Connection $connection): void
    {
        if ($this->defaultLogLevel === LogLevel::DEBUG) {
            $this->log('Exact Online Client: Starting a new token refresh.');
        }

        try {
            $this->getLock()->acquire(true);

            if ($this->defaultLogLevel === LogLevel::DEBUG) {
                $this->log('Exact Online Client: Acquired the refresh lock.');
            }

        } catch (LockAcquiringException | LockConflictedException $e) {
            throw new TokenRefreshException('Exact Online Client: Could not aquire the token lock to refresh the access token', $e->getCode(), $e);
        }
    }

    public function refreshAccessToken(Connection $connection): void
    {
        if ($this->defaultLogLevel === LogLevel::DEBUG) {
            $this->log('Exact Online Client: Refreshing the connection with up to date tokens from the token vault.');
        }

        $connection->reloadTokens();
    }

    public function updateAccessToken(Connection $connection): void
    {
        if ($this->defaultLogLevel === LogLevel::DEBUG) {
            $this->log('Exact Online Client: Storing the fresh tokens in the token vault.');
        }

        $this->tokenVault->store(
            $this->tokenVault->makeToken($connection->getAccessToken(), $connection->getRefreshToken(), $connection->getTokenExpires())
        );
    }

    public function releaseAccessTokenLock(Connection $connection): void
    {
        $this->getLock()->release();

        if ($this->defaultLogLevel === LogLevel::DEBUG) {
            $this->log('Exact Online Client: Releasing lock. Done with the token refresh.');
        }
    }
}
