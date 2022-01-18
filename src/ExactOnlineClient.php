<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient;

use Exception;
use Picqer\Financials\Exact\ApiException;
use PolarisDC\ExactOnline\BaseClient\Exceptions\AuthenticationException;
use PolarisDC\ExactOnline\BaseClient\Exceptions\ExactOnlineClientException;
use PolarisDC\ExactOnline\BaseClient\Interfaces\TokenVaultInterface;
use PolarisDC\ExactOnline\BaseClient\Traits\Loggable;

class ExactOnlineClient
{
    use Loggable;

    protected ClientConfiguration $clientConfiguration;
    protected TokenVaultInterface $tokenVault;

    protected ?Connection $connection;

    /** @var Callable */
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
    public function getConnection(?string $language = null): Connection
    {
        if (! isset($this->connection)) {
            $connection = $this->initializeConnection();
            $this->connection = $connection;
        }

        $this->connection->loadTokensFromVault();

        if ($this->connection->needsAuthentication()) {
            throw new AuthenticationException('Refresh token or initial authentication code is missing.');
        }

        // set language
        if ($language) {
            $this->connection->setCustomDescriptionLanguage($language);
        }

        // pass callback to Connection
        $this->connection->setExactOnlineConnectionAvailableCallback($this->exactOnlineConnectionAvailableCallback);

        try {
            // perform checks to see if we are allowed to make an API connection
            if (is_callable($this->connection->getExactOnlineConnectionAvailableCallback())) {
                call_user_func($this->connection->getExactOnlineConnectionAvailableCallback(), $this->connection, 'refresh token');
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
                return $this->getConnection($language);
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

    /**
     * @return bool
     */
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

    protected function initializeConnection(): Connection
    {
        $connection = new Connection();

        $connection->setTokenVault($this->tokenVault);
        $connection->applyConfiguration($this->clientConfiguration);
        $connection->setLogger($this->logger);

        return $connection;
    }

    public function setExactOnlineConnectionAvailableCallback(callable $exactOnlineConnectionAvailableCallback): self
    {
        $this->exactOnlineConnectionAvailableCallback = $exactOnlineConnectionAvailableCallback;
        return $this;
    }
}
