<?php

namespace Lanin\Laravel\ApiExceptions\Tests;

use Illuminate\Foundation\Application;
use Lanin\Laravel\ApiExceptions\ApiExceptionsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Get package providers.
     *
     * @param  Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ApiExceptionsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->singleton(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            'Lanin\Laravel\ApiExceptions\LaravelExceptionHandler'
        );
    }

    /**
     * Make protected/private class property accessible.
     *
     * @param string|object $class
     * @param string $name
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    protected function getPublicProperty($class, $name)
    {
        if (! is_string($class)) {
            $class = get_class($class);
        }

        $class    = new ReflectionClass($class);
        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * Make protected/private class method accessible.
     *
     * @param string $name
     * @param string|object $class
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function getPublicMethod($name, $class)
    {
        if (! is_string($class)) {
            $class = get_class($class);
        }

        $class  = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
