# Laravel-API-Exceptions
> All in one solution for exception for JSON REST APIs on Laravel and Lumen.

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, [Composer](https://getcomposer.org) and [Laravel](http://laravel.com) 5.1+ are required.

To get the latest version of Laravel Laravel-API-Debugger, simply add the following line to the require block of your `composer.json` file.

```
"lanin/laravel-api-exceptions": "^0.1.0"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel-API-Exceptions is installed, you need to register the service provider. Open up `config/app.php` and add the following to the providers key.

```php
Lanin\Laravel\ApiExceptions\ApiExceptionsServiceProvider::class,
```

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.
