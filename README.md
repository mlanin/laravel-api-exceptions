# Laravel-API-Exceptions
[![Travis](https://img.shields.io/travis/rust-lang/rust.svg)](https://travis-ci.org/mlanin/laravel-api-exceptions)

> All in one solution for exception for JSON REST APIs on Laravel and Lumen.

## About

The goal of this package is to provide you with a set of most common exceptions that may be needed while developing JSON REST API. It also:

* Handles exceptions output.
* Handles exceptions report to logs.
* Overwrites default Validator to make validation errors more verbose.
* Has a FormRequest that to handle validation errors and pass them to ApiExceptions layer.

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, [Composer](https://getcomposer.org) and [Laravel](http://laravel.com) 5.1+ are required.

To get the latest version of Laravel Laravel-API-Debugger, simply add the following line to the require block of your `composer.json` file.

For Laravel 5.1
```
"lanin/laravel-api-exceptions": "^0.1.0"
```

For Laravel 5.3
```
"lanin/laravel-api-exceptions": "^0.3.0"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel-API-Exceptions is installed, you need to register the service provider. Open up `config/app.php` and add the following to the providers key.

```php
Lanin\Laravel\ApiExceptions\ApiExceptionsServiceProvider::class,
```

### Exceptions

Every ApiException can be thrown as a normal exception and they will be automatically serialized to JSON with corresponding HTTP status, if user wants json:

```json
{
    "id": "not_found",
    "message": "Requested object not found."
}
```

This object will be also populated with trace info, when `APP_DEBUG` is true.

Also it can have `meta` attribute when there is additional info. For example for validation errors:
```json
{
	"id": "validation_failed",
	"message": "Validation failed.",
	"meta": {
		"errors": {
			"tags": [{
				"rule": "max.array",
				"message": "The tags may not have more than 10 items.",
				"parameters": ["10"]
			}]
		}
	}
}
```

For `ValidationApiException`, meta attribute has `errors` object that contains validations errors.
Every attribute of this object is a name of a request parameter to validate to and value is an array of errors with description.

### Views

Since version 0.3.0 for Laravel 5.3 package can also return html view of the error, if `Accept` header not equals `application/json`.

To change included views publish them via:

```
$ php artisan vendor:publish --tag=laravel-api-exceptions
```

### Handler

Extend your default exceptions handler with:

* `\Lanin\Laravel\ApiExceptions\LaravelExceptionHandler` for Laravel
* `\Lanin\Laravel\ApiExceptions\LumenExceptionHandler` for Lumen

And remove everything else. Example:

```php
<?php

namespace App\Exceptions;

use Lanin\Laravel\ApiExceptions\LaravelExceptionHandler;

class Handler extends LaravelExceptionHandler
{

}
```

### FormRequest

To use FormRequest extend all your Request classes with `\Lanin\Laravel\ApiExceptions\Support\Request`.
It will automatically support validation errors and pass them to the output.

It also has a very handy helper method `validatedOnly()` that returns from request only those items that are registered in rules method.

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.
