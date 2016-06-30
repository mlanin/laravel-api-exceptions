<?php

namespace Lanin\Laravel\ApiExceptions\Support;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lanin\Laravel\ApiExceptions\ApiException;
use Lanin\Laravel\ApiExceptions\InternalServerErrorApiException;
use Lanin\Laravel\ApiExceptions\MethodNotAllowedApiException;
use Lanin\Laravel\ApiExceptions\NotFoundApiException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RuntimeExceptionsHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ApiException
     * @throws InternalServerErrorApiException
     * @throws MethodNotAllowedApiException
     * @throws NotFoundApiException
     */
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ApiException $e) {
            throw $e;
        } catch (MethodNotAllowedHttpException $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : '';
            throw new MethodNotAllowedApiException($message, $e);
        } catch (NotFoundHttpException $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : '';
            throw new NotFoundApiException($message, $e);
        } catch (ModelNotFoundException $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : '';
            throw new NotFoundApiException($message, $e);
        } catch (\RuntimeException $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : '';
            throw new InternalServerErrorApiException($message, $e);
        }
    }
}
