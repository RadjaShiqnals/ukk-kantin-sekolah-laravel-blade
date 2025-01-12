<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->view('components.error-page', [
                'code' => '404',
                'heading' => 'Page Not Found',
                'message' => 'The page you are looking for does not exist.'
            ], 404);
        }

        return parent::render($request, $e);
    }
} 