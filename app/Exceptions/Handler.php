<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            
            return response()->json(
                [
                'success'=> 'false',
                'message' => $e->getMessage()
                ],
                $e->getCode()
            );
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
      
        if( env('APP_DEBUG') === false ) {

            if($exception instanceof ValidationException) {
                $response = $this->invalidJson($request, $exception);
                $message = json_decode($response->getContent(), true);
                return response()->json(
                    array_merge(
                        [ 'status' => 'false' ],
                        $message
                    ),
                    $response->getStatusCode()
                );
            }
            
            return response()->json(
                [
                'success'=> 'false',
                'message' => $exception->getMessage()
                ],
                $exception->getCode()
            );
        }

        return parent::render($request, $exception);
    }

}
