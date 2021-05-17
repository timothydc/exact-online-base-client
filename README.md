# Exact Online Base Client

This package provides a base API Client around the Exact Online API.

## Usage

The basic configuration looks like this:

```php
use PolarisDC\ExactOnline\BaseClient\ExactOnlineClient;
use PolarisDC\ExactOnline\BaseClient\ClientConfiguration;
use PolarisDC\ExactOnline\BaseClient\Authentication\TokenVault;

$clientConfiguration = new ClientConfiguration(
    'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', // client ID
    'yyyy', // client secret
    'zzzz',  // webhook secret
    'your-public-callback-url', // redirect URL - should be _exactly_ the same as the URL defined in the EOL app center
    'https://start.exactonline.be' // API base URL. Note the ".be" for Belgium
 );

$storagePath = 'path/to/tokens.json';
$tokenVault = new TokenVault();
$tokenVault->setStoragePath($storagePath);

$eolClient = new ExactOnlineClient($clientConfiguration, $tokenVault);
$eolClient->setLogger($this->logger); // optional logging
```

### Authorization
If you want to start a new authentication process, then call:

```php
$eolClient->startAuthorization();
```

During the authentication process, EOL will redirect you to your callback URL. There you will need to do:

```php
$eolClient->completeAuthorization('code-from-query-parameters');
```

Now your `$eolClient`-object is ready to make API requests:

```php
use Picqer\Financials\Exact\Item;

$items = (new Item($eolClient->getConnection())->get();
```

### Disconnect
If you want to disconnect and delete the access tokens, call the disconnection function on your client.
```php
$eolClient->disconnect();
```

### Internationalisation
If you want to retrieve the language dependent fields in a different language (e.g. Item description and Item long description), call the connection with a language parameter.
```php
use Picqer\Financials\Exact\Item;

$items = (new Item($eolClient->getConnection('FR-BE'))->get();
```
See also the `Support\ExactLocale.php` file. For more information, see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItems#goodToKnow

## Customization

### Storage driver

Tokens will be saved, by default, in a json file on the local disk. 

To make a custom driver, create a custom Vault class which extends the `TokenVaultInterface`-interface.

```php
use PolarisDC\ExactOnline\BaseClient\Authentication\TokenVaultInterface;
use PolarisDC\ExactOnline\BaseClient\Authentication\AccessTokenInterface;

class TokenVault implements TokenVaultInterface
{
    public function makeToken(?string $accesToken, ?string $refreshToken, int $expiresAt) : AccessTokenInterface{
        // TODO: Implement makeToken() method.
        // Provide the means here to create the object which implements the AccesstokenInterface
    }
    public function store(AccessTokenInterface $accessToken) : void
    {
        // TODO: Implement store() method.
    }
    public function retrieve() : AccessTokenInterface
    {
        // TODO: Implement retrieve() method.
    }
    public function clear() : void
    {
        // TODO: Implement clear() method.
    }
}
```

And also a custom Token class which extends the `AccessTokenInterface`-interface.

```php
use PolarisDC\ExactOnline\BaseClient\Authentication\AccessTokenInterface;

class Token extends Model implements AccessTokenInterface
{
    public function getAccessToken() : ?string
    {
        // TODO: Implement getAccessToken() method.
    }
    public function getRefreshToken() : ?string
    {
        // TODO: Implement getRefreshToken() method.
    }
    public function getExpiresAt() : int
    {
        // TODO: Implement getExpiresAt() method.
    }
}
```

## See Also
- [Laravel Exact Online Client](https://bitbucket.org/polaris-dc/exact-online-laravel-client)
- [Shopware Exact Online Client](https://bitbucket.org/polaris-dc/exact-online-shopware-client)

## Credits

- [Picqer/exact-php-client](https://github.com/picqer/exact-php-client)
- [PendoNL](https://github.com/PendoNL/laravel-exact-online)