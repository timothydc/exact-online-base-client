# Exact Online Base Client

This package provides a wrapper for the Exact Online API.

## Usage

The basic configuration looks like this:

```php
use PolarisDC\ExactOnline\BaseClient\ExactOnlineClient;
use PolarisDC\ExactOnline\BaseClient\ClientConfiguration;
use PolarisDC\ExactOnline\BaseClient\Authentication\TokenVault;
use Psr\Log\LogLevel;

$clientConfiguration = new ClientConfiguration(
    'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', // client ID
    'yyyy', // client secret
    'zzzz',  // webhook secret
    'your-public-callback-url', // redirect URL - should be _exactly_ the same as the URL defined in the EOL app center
    'https://start.exactonline.be' // API base URL. Note the ".be" for Belgium
 );

$tokenVault = new TokenVault();
$tokenVault->setStoragePath('path/to/tokens.json');

$client = new ExactOnlineClient($clientConfiguration, $tokenVault);
$client->setLogger($this->logger); // optional logging
$client->setDefaultLogLevel(LogLevel::ERROR); // optional log level
```

### Authorization

If you want to start a new authentication process, then call:

```php
$client->startAuthorization();
```

During the authentication process, EOL will redirect you to your callback URL. There you will need to do:

```php
$client->completeAuthorization('code-from-query-parameters');
```

Now your `$eolClient`-object is ready to make API requests:

```php
use Picqer\Financials\Exact\Item;

$items = (new Item($client->getConnection()))->get();
```

### Disconnect

If you want to disconnect and delete the access tokens, call the disconnection function on your client.

```php
$client->disconnect();
```

### Internationalisation

If you want to retrieve the language dependent fields in a different language (e.g. Item description and Item long description), call the connection with a language parameter.

```php
use Picqer\Financials\Exact\Item;

$items = (new Item($client->getConnection('FR-BE')))->get();
```

See also the `Support\ExactLocale.php` file. For more information, see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItems#goodToKnow

## Customization

### Storage driver

Tokens will be saved, by default, in a json file on the local disk.

To make a custom driver, create a custom Vault class which extends the `TokenVaultInterface`-interface.

```php
use PolarisDC\ExactOnline\BaseClient\Interfaces\AccessTokenInterface;
use PolarisDC\ExactOnline\BaseClient\Interfaces\TokenVaultInterface;

class TokenVault implements TokenVaultInterface
{
    public function makeToken(?string $accesToken, ?string $refreshToken, int $expiresAt): AccessTokenInterface
    {
    }
    
    public function store(AccessTokenInterface $accessToken): void
    {
    }
    
    public function retrieve(): AccessTokenInterface
    {
    }
    
    public function clear(): void
    {
    }
}
```

And also a custom Token class which extends the `AccessTokenInterface`-interface.

```php
use PolarisDC\ExactOnline\BaseClient\Interfaces\AccessTokenInterface;

class Token implements AccessTokenInterface
{
    public function getAccessToken(): ?string
    {
    }
    
    public function getRefreshToken(): ?string
    {
    }
    
    public function getExpiresAt(): int
    {
    }
}
```