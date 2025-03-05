<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\MaxExecutionTimeException;

class SocketTimeOutExceptionHandler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MaxExecutionTimeException) {
            return response()->json(['error' => 'Maximum execution time exceeded. Please try again later.'], 500);
        }

        return parent::render($request, $exception);
    }
}
