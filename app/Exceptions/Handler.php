<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (\League\OAuth2\Server\Exception\OAuthServerException $e) {
            if($e->getCode() == 9)
                return false;
        });
    }


    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            return $this->handleApiException($request, $exception);
            $retval = parent::render($request, $exception);
        } else {
            $retval = parent::render($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }


    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];
       // $response['success'] = false;
        switch ($statusCode) {
            case 400:
                $response['error'] = 'Bad Request';
                break;
            case 401:
                $response['error'] = 'Unauthorized';
                break;
            case 403:
                $response['error'] = 'Forbidden';
                break;
            case 404:
                $response['error'] = 'Not Found';
                break;
            case 405:
                $response['error'] = 'Method Not Allowed';
                break;
            case 408:
                $response['error'] = 'Request Timeout';
                break;
            case 422:
                $response['error'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['error'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' :  (method_exists($exception, 'getMessage') ? $exception->getMessage() :'');
                break;
        }

        if(config('app_settings.enable_slack_log_notifications.value')) {
            if (!in_array($statusCode, [401, 422, 403, 404, 405])) {
                Log::channel('slack')->error(method_exists($exception, 'getMessage') ? $exception->getMessage() : '' , [
                    'File' => (method_exists($exception, 'getFile')) ? $exception->getFile() : '',
                    'Line' => (method_exists($exception, 'getLine')) ? $exception->getLine() : '',
                    'Code' => (method_exists($exception, 'getCode')) ? $exception->getCode() : '',
                ]);
            }
        }

        if (config('app.debug')) {
            if (method_exists($exception, 'getMessage')) {
                $response['error'] = $exception->getMessage();
            }
            $response['trace'] =  (method_exists($exception, 'getTrace')) ? $exception->getTrace() : '';
            $response['code'] = (method_exists($exception, 'getCode')) ? $exception->getCode() : '';
        }

        $response['error_code'] = $statusCode;

        return response()->json($response, $statusCode);
    }
}
