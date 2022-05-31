<?php
declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient;

use GuzzleHttp\Middleware;
use Picqer\Financials\Exact\ApiException;
use Picqer\Financials\Exact\Connection as PicqerConnection;
use PolarisDC\ExactOnline\BaseClient\Exceptions\AuthenticationException;
use PolarisDC\ExactOnline\BaseClient\Exceptions\RateLimitException;
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

    /**
     * @var callable
     */
    protected $exactOnlineConnectionAvailableCallback;

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

    /**
     * @throws ApiException
     * @throws AuthenticationException
     * @throws RateLimitException
     */
    public function get($url, array $params = [], array $headers = [])
    {
        try {
            // perform checks to see if we are allowed to make an API connection
            if (\is_callable($this->getExactOnlineConnectionAvailableCallback())) {
                \call_user_func($this->getExactOnlineConnectionAvailableCallback(), $this, $url);
            }

            return parent::get($url, $params, $headers);
        } catch (ApiException $e) {

            // catch rate limit exceptions
            if ($e->getCode() === RateLimitException::CODE) {

                // log the request, so we know what request triggered the rate limit.
                $this->logFailedRequest($e, 'GET: ' . $url, $params);

                throw new RateLimitException($e->getMessage(), $e->getCode(), $e, $this->getRateLimits(), $this->getClientId());
            }

            // catch "Could not acquire or refresh tokens" exceptions
            if ($e->getCode() === 0 || str_contains($e->getMessage(), 'Could not acquire or refresh tokens')) {
                throw new AuthenticationException($e->getMessage(), AuthenticationException::CODE, $e, $this->getClientId());
            }

            // rethrow all the rest
            throw $e;
        }
    }

    /**
     * @throws ApiException
     * @throws AuthenticationException
     * @throws RateLimitException
     */
    public function post($url, $body)
    {
        try {
            // perform checks to see if we are allowed to make an API connection
            if (\is_callable($this->getExactOnlineConnectionAvailableCallback())) {
                \call_user_func($this->getExactOnlineConnectionAvailableCallback(), $this, $url);
            }

            return parent::post($url, $body);
        } catch (ApiException $e) {

            // catch rate limit exceptions
            if ($e->getCode() === RateLimitException::CODE) {

                // log the request, so we know what request triggered the rate limit.
                $this->logFailedRequest($e, 'POST: ' . $url, $body);

                throw new RateLimitException($e->getMessage(), $e->getCode(), $e, $this->getRateLimits(), $this->getClientId());
            }

            // catch "Could not acquire or refresh tokens" exceptions
            if ($e->getCode() === 0 || str_contains($e->getMessage(), 'Could not acquire or refresh tokens')) {
                throw new AuthenticationException($e->getMessage(), AuthenticationException::CODE, $e, $this->getClientId());
            }

            // rethrow all the rest
            throw $e;
        }
    }

    /**
     * @throws ApiException
     * @throws AuthenticationException
     * @throws RateLimitException
     */
    public function put($url, $body)
    {
        try {
            // perform checks to see if we are allowed to make an API connection
            if (\is_callable($this->getExactOnlineConnectionAvailableCallback())) {
                \call_user_func($this->getExactOnlineConnectionAvailableCallback(), $this, $url);
            }

            return parent::put($url, $body);
        } catch (ApiException $e) {

            // catch rate limit exceptions
            if ($e->getCode() === RateLimitException::CODE) {

                // log the request, so we know what request triggered the rate limit.
                $this->logFailedRequest($e, 'PUT: ' . $url, $body);

                throw new RateLimitException($e->getMessage(), RateLimitException::CODE, $e, $this->getRateLimits(), $this->getClientId());
            }

            // catch "Could not acquire or refresh tokens" exceptions
            if ($e->getCode() === 0 || str_contains($e->getMessage(), 'Could not acquire or refresh tokens')) {
                throw new AuthenticationException($e->getMessage(), AuthenticationException::CODE, $e, $this->getClientId());
            }

            // rethrow all the rest
            throw $e;
        }
    }

    /**
     * @throws ApiException
     * @throws AuthenticationException
     * @throws RateLimitException
     */
    public function delete($url)
    {
        try {
            // perform checks to see if we are allowed to make an API connection
            if (\is_callable($this->getExactOnlineConnectionAvailableCallback())) {
                \call_user_func($this->getExactOnlineConnectionAvailableCallback(), $this, $url);
            }

            return parent::delete($url);
        } catch (ApiException $e) {

            // catch rate limit exceptions
            if ($e->getCode() === RateLimitException::CODE) {

                // log the request, so we know what request triggered the rate limit.
                $this->logFailedRequest($e, 'DELETE: ' . $url);

                throw new RateLimitException($e->getMessage(), $e->getCode(), $e, $this->getRateLimits(), $this->getClientId());
            }

            // catch "Could not acquire or refresh tokens" exceptions
            if ($e->getCode() === 0 || str_contains($e->getMessage(), 'Could not acquire or refresh tokens')) {
                throw new AuthenticationException($e->getMessage(), AuthenticationException::CODE, $e, $this->getClientId());
            }

            // rethrow all the rest
            throw $e;
        }
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

    public function isAuthorized(): bool
    {
        $accessToken = $this->tokenVault->retrieve();

        return $accessToken->getAccessToken() && $accessToken->getRefreshToken() && $accessToken->getExpiresAt();
    }

    /**
     * Sets the language sensitive properties such as descriptions in a specific language.
     *
     * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItems#goodToKnow
     */
    public function setCustomDescriptionLanguage(string $language): void
    {
        $this->insertMiddleWare(Middleware::mapRequest(static fn (RequestInterface $request) => $request->withHeader('CustomDescriptionLanguage', $language)));
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
        $this->setTokenExpires($accessToken->getExpiresAt() - 15);
    }

    public function reloadTokens(): void
    {
        $this->loadTokensFromVault();
    }

    public function getRateLimits(): RateLimits
    {
        return new RateLimits(
            $this->minutelyLimit,
            $this->minutelyLimitRemaining,
            $this->minutelyLimitReset,
            $this->dailyLimit,
            $this->dailyLimitRemaining,
            $this->dailyLimitReset
        );
    }

    /**
     * @throws TokenRefreshException
     */
    public function acquireAccessTokenLock(self $connection): void
    {
        $this->log('Exact Online Client: Starting a new token refresh.', [
            'access token' => $connection->getAccessToken(),
            'refresh token' => $connection->getRefreshToken(),
        ]);

        try {
            $this->getLock()->acquire(true);

            $this->log('Exact Online Client: Acquired the refresh lock.');
        } catch (LockAcquiringException|LockConflictedException $e) {
            throw new TokenRefreshException('Exact Online Client: Could not aquire the token lock to refresh the access token', $e->getCode(), $e);
        }
    }

    public function refreshAccessToken(self $connection): void
    {
        $this->log('Exact Online Client: Refreshing the connection with up to date tokens from the token vault.');

        $connection->reloadTokens();
    }

    public function updateAccessToken(self $connection): void
    {
        $this->log('Exact Online Client: Storing the fresh tokens in the token vault.');

        $this->tokenVault->store(
            $this->tokenVault->makeToken($connection->getAccessToken(), $connection->getRefreshToken(), $connection->getTokenExpires())
        );
    }

    public function releaseAccessTokenLock(self $connection): void
    {
        $this->getLock()->release();

        $this->log('Exact Online Client: Releasing lock. Done with the token refresh.', [
            'access token' => $connection->getAccessToken(),
            'refresh token' => $connection->getRefreshToken(),
        ]);
    }

    public function setExactOnlineConnectionAvailableCallback(?callable $exactOnlineConnectionAvailableCallback): self
    {
        $this->exactOnlineConnectionAvailableCallback = $exactOnlineConnectionAvailableCallback;

        return $this;
    }

    public function getExactOnlineConnectionAvailableCallback(): ?callable
    {
        return $this->exactOnlineConnectionAvailableCallback;
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

    protected function logFailedRequest(\Exception $exception, string $url, $params = null): void
    {
        $this->log($exception->getMessage(), [
            'url' => $url,
            'context' => $params,
            'minutely_limit_remaining' => $this->getMinutelyLimitRemaining(),
            'daily_limit_remaining' => $this->getDailyLimitRemaining(),
        ], LogLevel::WARNING);
    }
}
