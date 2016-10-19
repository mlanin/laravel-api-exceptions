<?php

namespace Lanin\Laravel\ApiExceptions\Tests;

use Illuminate\Support\Str;
use Lanin\Laravel\ApiExceptions\ApiExceptionsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit_Framework_ExpectationFailedException as PHPUnitException;
use ReflectionClass;


abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
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
     * @param  \Illuminate\Foundation\Application $app
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
     * @param  string|object $class
     * @param  string $name
     * @return \ReflectionProperty
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
     * @param  string $name
     * @param  string|object $class
     * @return \ReflectionMethod
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


    /**
     * Asserts that the response JSON contains the given path.
     *
     * @param string $path
     *
     * @return $this
     *
     * @throws PHPUnitException
     */
    protected function seeJsonMatchesPath($path)
    {
        $response = json_decode($this->response->getContent(), true);

        // Remove heading $. symbols
        $search = ltrim($path, '$.');

        // Using random string to protect against null values
        $notFoundString = Str::random(6);

        try {
            $this->assertNotEquals(
                array_get($response, $search, $notFoundString),
                $notFoundString
            );
        } catch (PHPUnitException $e) {
            throw new PHPUnitException("Unable to find provided path [{$path}] in received JSON [{$this->response->getContent()}].");
        }

        return $this;
    }

    /**
     * Return value from the resulting JSON by path.
     *
     * @param $path
     *
     * @return mixed
     */
    protected function getValueFromJsonByPath($path)
    {
        $response = json_decode($this->response->getContent(), true);

        // Remove heading $. symbols
        $search = ltrim($path, '$.');

        // Using random string to protect against null values
        $notFoundString = Str::random(6);

        try {
            $value = array_get($response, $search, $notFoundString);

            $this->assertNotEquals(
                $value,
                $notFoundString
            );
        } catch (PHPUnitException $e) {
            throw new PHPUnitException("Unable to find provided path [{$path}] in received JSON [{$this->response->getContent()}].");
        }

        return $value;
    }
}
