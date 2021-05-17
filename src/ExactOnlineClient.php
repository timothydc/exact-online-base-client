<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient;

use Exception;
use PolarisDC\ExactOnline\BaseClient\Exceptions\AuthenticationException;
use PolarisDC\ExactOnline\BaseClient\Exceptions\ExactOnlineClientException;
use PolarisDC\ExactOnline\BaseClient\Authentication\TokenVaultInterface;
use PolarisDC\ExactOnline\BaseClient\Support\Loggable;

class ExactOnlineClient
{
    use Loggable;

    protected ClientConfiguration $clientConfiguration;
    protected TokenVaultInterface $tokenVault;

    public function __construct(ClientConfiguration $clientConfiguration, TokenVaultInterface $tokenVault)
    {
        $this->clientConfiguration = $clientConfiguration;
        $this->tokenVault = $tokenVault;
    }

    /**
     * @param string|null $language
     * @return Connection
     * @throws AuthenticationException
     * @throws ExactOnlineClientException
     */
    public function getConnection(?string $language = null): Connection
    {
        $connection = $this->initializeConnection();
        $connection->loadTokensFromVault();

        if ($connection->needsAuthentication()) {
            throw new AuthenticationException('Refresh token or initial authentication code is missing.');
        }

        // set language
        if ($language) {
            $connection->setCustomDescriptionLanguage($language);
        }

        try {
            // actually connect
            $connection->connect();
        } catch (Exception $e) {
            // catch all underlying exceptions and throw our own
            throw new ExactOnlineClientException($e->getMessage(), $e->getCode(), $e);
        }

        return $connection;
    }

    /**
     * @throws ExactOnlineClientException
     */
    public function startAuthorization(): void
    {
        $this->log('Exact Online Client: Starting Exact Online authorization flow.');

        $connection = $this->initializeConnection();

        if ($connection->isAuthorized()) {
            throw new ExactOnlineClientException('The Exact Online Client has already been authorized.');
        }

        $this->log('Exact Online Client: Redirecting to Exact Online for authorization.');

        // redirect to the authorization URL
        $connection->redirectForAuthorization();
    }

    /**
     * @param string $authorizationCode
     * @return void
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
            $this->log('Exact Online Client: Received authorization callback.');

            $connection->setAuthorizationCode($authorizationCode);
            $connection->connect();

            $this->log('Exact Online Client: Authorization flow completed successfully.');
        } catch (Exception $e) {
            // catch all underlying exceptions and throw our own
            $this->log('Exact Online Client: Exception during authorization flow.');
            throw new ExactOnlineClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function disconnect(): void
    {
        $this->log('Exact Online Client: The Exact client is now disconnected.');
        $this->tokenVault->clear();
    }

    protected function initializeConnection(): Connection
    {
        $connection = new Connection();

        $connection->setTokenVault($this->tokenVault);
        $connection->applyConfiguration($this->clientConfiguration);
        $connection->setLogger($this->logger);

        return $connection;
    }
}
