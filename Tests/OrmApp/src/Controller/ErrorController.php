<?php

namespace OrmApp\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController
{
    public function showAction(\Throwable $exception)
    {
        $code = $exception instanceof HttpException ? $exception->getStatusCode() : 500;

        return new JsonResponse(['error' => $exception->getMessage()], $code);
    }
}
