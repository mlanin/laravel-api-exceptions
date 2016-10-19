<?php namespace Lanin\Laravel\ApiExceptions\Tests;

class TestExceptionsOutput extends TestCase
{
    public function test_404_error_for_json()
    {
        $this->json('POST', '/foo')
            ->seeStatusCode(404)
            ->seeJsonContains([
                'id' => 'not_found',
            ]);
    }

    public function test_404_error_for_html()
    {
        $this->get('/foo')
            ->seeStatusCode(404)
            ->see('Not Found');
    }

    public function test_validation_error()
    {
        $this->app['router']->get('foo', function () {
            $validator = \Validator::make([], ['name' => 'required']);
            $validator->validate();
        });

        $this->json('GET', '/foo')
            ->seeStatusCode(422)
            ->seeJsonContains([
                'id' => 'validation_failed',
            ])
            ->seeJsonMatchesPath('$.meta.errors.name');
    }

    public function test_internal_error_error()
    {
        $this->app['router']->get('foo', function () {
            $new = new UndefinedClass();
        });

        $this->json('GET', '/foo')
            ->seeStatusCode(500)
            ->seeJsonContains([
                'id' => 'internal_server_error',
            ]);
    }

    public function test_internal_error_in_debug_mode()
    {
        putenv('APP_DEBUG=true');

        $this->app['router']->get('foo', function () {
            $new = new UndefinedClass();
        });

        $this->json('GET', '/foo')
            ->seeStatusCode(500)
            ->seeJsonContains([
                'id' => 'fatal_throwable_error',
            ])
            ->seeJsonMatchesPath('$.trace');
    }
}