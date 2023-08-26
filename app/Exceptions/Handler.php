<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        // 
    }
    
    /**
     * Render the exception into an HTTP response.
     */
    public function render($request, \Throwable $e) {
        if ($e instanceof HttpException)
        {
            return response()->json([
                    'status'  => $e->getStatusCode(),
                    'message' => $e->getMessage()
                ], $e->getStatusCode());
        }

        if ($e instanceof ModelNotFoundException) 
        {
            $uriArray = explode('/', $request->getUri());
            return response()->json([
                    'status'  => 404,
                    'message' => 'Task with id ' .$uriArray[5]. ' not found',
                ], 404);
        }

        return response()->json([
            'status'  => 500,
            'message' => $e->getMessage()
        ], 500);
    }
}
