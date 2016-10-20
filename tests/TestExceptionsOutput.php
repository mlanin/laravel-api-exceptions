<?php namespace Lanin\Laravel\ApiExceptions\Tests;

use Illuminate\Foundation\Http\FormRequest;
use Lanin\Laravel\ApiExceptions\Support\Request;
use Symfony\Component\Debug\Exception\FatalErrorException;

class TestExceptionsOutput extends TestCase
{
    /**
     * @test
     */
    public function test_404_error_for_json()
    {
        $this->json('POST', '/foo')
            ->seeStatusCode(404)
            ->seeJsonContains([
                'id' => 'not_found',
            ]);
    }

    /**
     * @test
     */
    public function test_404_error_for_html()
    {
        $this->get('/foo')
            ->seeStatusCode(404)
            ->see('Not Found');
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
            ->seeStatusCode(422)
            ->seeJsonContains([
                'id' => 'validation_failed',
            ])
            ->seeJsonMatchesPath('$.meta.errors.name');
    }

    /**
     * @test
     */
    public function test_internal_error_error()
    {
        $this->app['router']->get('foo', function () {
            throw new FatalErrorException('Fatal error.', 0, 1, __FILE__, __LINE__);
        });

        $this->json('GET', '/foo')
            ->seeStatusCode(500)
            ->seeJsonContains([
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
            throw new FatalErrorException('Fatal error.', 0, 1, __FILE__, __LINE__);
        });

        $this->json('GET', '/foo')
            ->seeStatusCode(500)
            ->seeJsonContains([
                'id' => 'fatal_error_exception',
                'message' => 'Fatal error.'
            ])
            ->seeJsonMatchesPath('$.trace');
    }

    /**
     * @test
     */
    public function test_form_request_validation_fail()
    {
        $this->app['router']->post('foo', function (FooRequest $request) {

        });

        $this->json('POST', '/foo', ['foo' => 'bar'])
            ->seeStatusCode(422)
            ->seeJsonContains([
                'id' => 'validation_failed',
            ])
            ->seeJsonMatchesPath('$.meta.errors.name');
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
            ->seeStatusCode(200)
            ->seeJson([
                'name' => 'bar',
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