<?php

namespace Lanin\Laravel\ApiExceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Lanin\Laravel\ApiExceptions\Contracts\DontReport;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LumenExceptionHandler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        DontReport::class,
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
        if ($this->shouldReport($e) || ! env('APP_DEBUG')) {
            try {
                $logger = app('Psr\Log\LoggerInterface');
            } catch (Exception $ex) {
                throw $e; // throw the original exception
            }

            $logger->error($e instanceof ApiException ? $e->toReport() : $e);
        }
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
        switch (true) {
            case $e instanceof ApiException:
                break;
            case $e instanceof AuthorizationException:
                $e = new UnauthorizedApiException('', $e);
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

        $response = response()->json($e, $e->getCode(), $e->getHeaders());

        return $response;
    }
}
