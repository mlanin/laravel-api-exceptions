<?php

namespace Lanin\Laravel\ApiExceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionHandlerTrait
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
        Contracts\DontReport::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e instanceof ApiException ? $e->toReport() : $e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $e = $this->resolveException($e);

        $response = $request->expectsJson() || env('DEBUG')
            ? $this->renderForApi($e)
            : $this->renderHtmlPage($e);

        return $response->withException($e);
    }

    /**
     * Render exceptions for json API.
     *
     * @param  ApiException $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderForApi(ApiException $e)
    {
        return response()->json($e, $e->getCode(), $e->getHeaders());
    }

    /**
     * Render exception for common html request.
     *
     * @param  ApiException $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function renderHtmlPage(ApiException $e)
    {
        $status = $e->getCode();

        return view()->exists("errors.{$status}")
            ? response()->view("errors.{$status}", ['exception' => $e], $status, $e->getHeaders())
            : $this->renderForApi($e);
    }

    /**
     * Define exception.
     *
     * @param  Exception $e
     * @return ApiException
     */
    protected function resolveException(Exception $e)
    {
        switch (true) {
            case $e instanceof ApiException:
                break;
            case $e instanceof AuthorizationException:
                $e = new ForbiddenApiException('', $e);
                break;
            case $e instanceof AuthenticationException:
                $e = new UnauthorizedApiException('', $e);
                break;
            case $e instanceof ValidationException:
                $e = new ValidationFailedApiException($e->validator->getMessageBag()->toArray(), '');
                break;
            case $e instanceof MethodNotAllowedHttpException:
                $e = new MethodNotAllowedApiException('', $e);
                break;
            case $e instanceof ModelNotFoundException:
            case $e instanceof NotFoundHttpException:
                $e = new NotFoundApiException();
                break;
            default:
                $e = new InternalServerErrorApiException('', $e);
                break;
        }

        return $e;
    }
}
