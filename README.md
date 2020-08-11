# Integrate with Cloudflare caching
```composer require ziffmedia/laravel-cloudflare```

```LaravelCloudflare::make()``` in your NovaServiceProvider under tools.

Create config/cloudflare.php, add Cloudflare email, key, zone values. 
Add allowable domains in an array as 'domains' in the cloudflare config file.

To install dependencies:

```shell script
docker-compose run --rm composer install
docker-compose run --rm npm install
```

To compile tool for distribution:

```shell script
docker-compose run --rm npm run prod
```
