<?php

namespace Lanin\Laravel\ApiExceptions;

use Illuminate\Support\ServiceProvider;
use Lanin\Laravel\ApiExceptions\Support\Validator;

class ApiExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set custom validation resolver
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            return new Validator($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
