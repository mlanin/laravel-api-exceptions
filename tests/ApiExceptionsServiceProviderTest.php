<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions\Tests;

use Lanin\Laravel\ApiExceptions\ApiExceptionsServiceProvider;

class ApiExceptionsServiceProviderTest extends TestCase
{
    /** @var ApiExceptionsServiceProvider */
    private $provider;

    public function setUp(): void
    {
        parent::setUp();
        $this->provider = $this->app->getProvider(ApiExceptionsServiceProvider::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->provider);
    }

    /**
     * @test
     */
    public function it_overwrites_validator()
    {
        $factory = $this->app['validator'];

        $property = $this->getPublicProperty($factory, 'resolver');
        $validator = $property->getValue($factory);
        $this->assertInstanceOf(\Closure::class, $validator);
    }
}
