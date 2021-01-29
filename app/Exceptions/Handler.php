<?php

namespace App\Exceptions;

use App\Utils\ErrorCode;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->validate_json(ErrorCode::NO_PARAM_VALIDATE);
        }

        if($exception instanceof AuthorizationException){
            return $this->validate_json(ErrorCode::UNAUTHORIZED);
        }
        return parent::render($request, $exception);
    }

    public function validate_json($error_code)
    {
        return response()->json([
            'code' => $error_code['code'],
            'data' => [],
            'message' => $error_code['message'],
            'time' => time()
        ]);
    }

    public function unauthorized($error_code){
        return response()->json([
            'code' => $error_code['code'],
            'data' => [],
            'message' => $error_code['message'],
            'time' => time()
        ]);
    }
}
