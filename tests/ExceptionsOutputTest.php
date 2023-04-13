<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lanin\Laravel\ApiExceptions\Support\Request;
use Symfony\Component\ErrorHandler\Error\FatalError;

class ExceptionsOutputTest extends TestCase
{
    /**
     * @test
     */
    public function test_404_error_for_json()
    {
        $this->json('POST', '/foo')
            ->assertStatus(404)
            ->assertJsonFragment([
                'id' => 'not_found',
            ]);
    }

    /**
     * @test
     */
    public function test_404_error_for_html()
    {
        $this->get('/foo')
            ->assertStatus(404)
            ->assertSee('Requested object not found');
    }

    /**
     * @test
     */
    public function test_validation_error()
    {
        $this->app['router']->get('foo', function () {
            $validator = \Validator::make([], ['name' => 'required']);
            $validator->validate();
        });

        $this->json('GET', '/foo')
            ->assertStatus(422)
            ->assertJsonFragment([
                'id' => 'validation_failed',
            ])
            ->assertJsonStructure([
                'meta' => [
                    'errors' => [
                        'name'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function test_internal_error_error()
    {
        $this->app['router']->get('foo', function () {
            throw new \Exception('Fatal error');
        });

        $this->json('GET', '/foo')
            ->assertStatus(500)
            ->assertJsonFragment([
                'id' => 'internal_server_error',
            ]);
    }

    /**
     * @test
     */
    public function test_internal_error_in_debug_mode()
    {
        putenv('APP_DEBUG=true');

        $this->app['router']->get('foo', function () {
            throw new \Exception('Fatal error');
        });

        $this->json('GET', '/foo')
            ->assertStatus(500)
            ->assertJsonFragment([
                'id' => 'exception',
                'message' => 'Fatal error'
            ])
            ->assertJsonStructure([
                'trace',
            ]);
    }

    /**
     * @test
     */
    public function test_form_request_validation_fail()
    {
        $this->app['router']->post('foo', function (FooRequest $request) {

        });

        $this->json('POST', '/foo', ['foo' => 'bar'])
            ->assertStatus(422)
            ->assertJsonFragment([
                'id' => 'validation_failed',
            ])
            ->assertJsonStructure([
                'meta' => [
                    'errors' => [
                        'name'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function test_form_request_validation_passed()
    {
        $this->app['router']->post('foo', function (FooRequest $request) {
            return response()->json(['foo' => $request->validatedOnly()]);
        });

        $this->json('POST', '/foo', ['name' => 'bar'])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'bar',
            ]);
    }

    /**
     * @test
     */
    public function test_model_not_found_fail()
    {
        $this->app['router']->get('foo', function () {
            throw new ModelNotFoundException();
        });

        $this->json('GET', '/foo')
            ->assertStatus(404)
            ->assertJsonFragment([
                'id' => 'not_found',
            ]);
    }
}

class FooRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
