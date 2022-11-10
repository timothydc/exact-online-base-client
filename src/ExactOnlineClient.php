<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient;

use Exception;
use Picqer\Financials\Exact\ApiException;
use PolarisDC\ExactOnline\BaseClient\Exceptions\AuthenticationException;
use PolarisDC\ExactOnline\BaseClient\Exceptions\ExactOnlineClientException;
use PolarisDC\ExactOnline\BaseClient\Interfaces\TokenVaultInterface;
use PolarisDC\ExactOnline\BaseClient\Support\Locale;
use PolarisDC\ExactOnline\BaseClient\Traits\Loggable;

class ExactOnlineClient
{
    use Loggable;

    protected ClientConfiguration $clientConfiguration;
    protected TokenVaultInterface $tokenVault;
    protected ?string $cachedLocale = null;
    protected ?Connection $connection;

    /**
     * @var callable
     */
    protected $exactOnlineConnectionAvailableCallback;

    public function __construct(ClientConfiguration $clientConfiguration, TokenVaultInterface $tokenVault)
    {
        $this->clientConfiguration = $clientConfiguration;
        $this->tokenVault = $tokenVault;
    }

    /**
     * @throws AuthenticationException
     * @throws ExactOnlineClientException
     */
    public function getConnection(?string $exactOnlineLocale = null): Connection
    {
        $exactOnlineLocale = $this->configureExactOnlineLocale($exactOnlineLocale);

        if (! isset($this->connection)) {
            $connection = $this->initializeConnection();
            $this->connection = $connection;
        }

        $this->connection->loadTokensFromVault();

        if ($this->connection->needsAuthentication()) {
            throw new AuthenticationException('Refresh token or initial authentication code is missing.');
        }

        // set language
        if ($exactOnlineLocale) {
            $this->connection->setCustomDescriptionLanguage($exactOnlineLocale);
        }

        // pass callback to Connection
        $this->connection->setExactOnlineConnectionAvailableCallback($this->exactOnlineConnectionAvailableCallback);

        try {
            // perform checks to see if we are allowed to make an API connection
            if (\is_callable($this->connection->getExactOnlineConnectionAvailableCallback())) {
                \call_user_func($this->connection->getExactOnlineConnectionAvailableCallback(), $this->connection, 'refresh token');
            }

            // actually connect
            $this->connection->connect();
        } catch (ApiException $e) {
            if ($e->getCode() === 0 || str_contains($e->getMessage(), 'Could not acquire or refresh tokens')) {
                // remove the invalid access token
                $this->tokenVault->remove($this->connection->getAccessToken());

                // reload the tokens, with another (more) valid token
                $this->connection->loadTokensFromVault();

                // retry the request
                return $this->getConnection($exactOnlineLocale);
            }

            // rethrow all the rest
            throw new ExactOnlineClientException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->connection;
    }

    /**
     * @throws ExactOnlineClientException
     */
    public function startAuthorization(string $state = ''): void
    {
        $this->log('Exact Online Client: Starting authorization flow.');

        $connection = $this->initializeConnection();

        if ($connection->isAuthorized()) {
            throw new ExactOnlineClientException('The Exact Online Client: Already been authorized.');
        }

        $connection->setState($state);

        // redirect to the authorization URL
        $connection->redirectForAuthorization();
    }

    /**
     * @throws ExactOnlineClientException
     */
    public function completeAuthorization(string $authorizationCode): void
    {
        $connection = $this->initializeConnection();

        if ($connection->isAuthorized()) {
            throw new ExactOnlineClientException('The Exact Online Client has already been authorized.');
        }

        try {
            // authorize first time with authorization code (= get first access token)
            $connection->setAuthorizationCode($authorizationCode);
            $connection->connect();

            $this->log('Exact Online Client: Authorization flow completed successfully.');
        } catch (Exception $e) {
            // catch all underlying exceptions and throw our own
            $this->log('Exact Online Client: Exception during authorization flow: ' . $e->getMessage());

            throw new ExactOnlineClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function clientIsAuthorized(): bool
    {
        return $this->initializeConnection()->isAuthorized();
    }

    public function disconnect(): void
    {
        $this->log('Exact Online Client: Disconnected.');
        $this->tokenVault->clear();
        $this->connection = null;
    }

    public function setExactOnlineConnectionAvailableCallback(callable $exactOnlineConnectionAvailableCallback): self
    {
        $this->exactOnlineConnectionAvailableCallback = $exactOnlineConnectionAvailableCallback;

        return $this;
    }

    public function getExactOnlineConnectionAvailableCallback(): callable
    {
        return $this->exactOnlineConnectionAvailableCallback;
    }

    protected function initializeConnection(): Connection
    {
        $connection = new Connection();

        $connection->setTokenVault($this->tokenVault);
        $connection->applyConfiguration($this->clientConfiguration);
        $connection->setLogger($this->logger);

        return $connection;
    }

    protected function configureExactOnlineLocale(?string $language = null): ?string
    {
        // load language from "cache" when it was not given, or attempt to load the language from the client configuration
        if (! $language) {
            $language = $this->cachedLocale ?: $this->clientConfiguration->getLanguage();
        }

        // check that it is a valid language
        if ($language) {
            $language = Locale::convertIso6391toExactLocale($language);
        }

        // if the language is given and is different from the cached language, reset the connection
        if ($language && $this->cachedLocale !== $language) {
            $this->connection = null;
            $this->cachedLocale = $language;
        }

        return $language;
    }
}
