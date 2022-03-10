<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->renderable(function (Throwable $e, $request) {
            if (!Request::is('api/*')) {

                $classString    = get_class($e);
                $classSplit     = explode("\\", $classString);
                $count          = count($classSplit);
                $class          = $classSplit[$count-1];

                switch ($class) {

                    case 'AuthorizationException':
                        $message    = ['message' => [__('Authorization exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'AccessDeniedHttpException':
                        $message    = ['message' => [__('Access denied http exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'AuthenticationException':

                        $message    = ['message' => [__('Authentication exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'NotFoundHttpException':

                        $message    = ['message' => [__('Not found http exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'Swift_TransportException':

                        $message    = ['message' => [__("Email Transport Exception")]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'ModelNotFoundException':

                        $message    = ['message' => [__('Model not found exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'MethodNotAllowedHttpException':

                        $message    = ['message' => [__('Method not allowed http exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'QueryException':
                        $message    = ['message' => [__('Query exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'InvalidArgumentException':
                        $message    = ['message' => [__('Invalid argument exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'TokenMismatchException':
                        $message    = ['message' => [__('Token mismatch exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    case 'ErrorException':
                        $message    = ['message' => [__('Error exception')]];
                        $status     = 'error';
                        $data       = false;
                        break;

                    default:
                        $message = ['message' => [$class]];
                        $status = 'error';
                        $data = false;
                        break;
                };

                return response([
                    'data'      => $data,
                    'status'    => $status,
                    'message'   => $message
                ], 200);

            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
