# Laravel Cloudflare tools

A Laravel package with integrated Nova tools for interacting with Cloudflare. 

## Installation

First you must install the package into your Laravel application. 

```console
composer require ziffmedia/laravel-cloudflare
```

### Nova Integration

If you are using the Nova cache purge tools you should register the tool with Nave in your NovaServiceProvider:

```php
// app/Providers/NovaServiceProvdier.php
public function tools()
{
    return [
        // ...,
        new \ZiffMedia\LaravelCloudflare\LaravelCloudflare(),
    ];
}
```

Finally, you need to publish the config file to your application:

```console
   php artisan vendor:publish --provider="ZiffMedia\LaravelCloudflare\ToolServiceProvider" --tag="config" 
```

You should include your Cloudflare zone and API keys as env variables.

## Development

To install dependencies:

```shell script
docker-compose run --rm composer install
docker-compose run --rm npm install
```

To compile tool for distribution:

```shell script
docker-compose run --rm npm run prod
```
