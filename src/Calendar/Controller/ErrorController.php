<?php
namespace Calendar\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    public function exceptionAction(FlattenException $exception)
    {
        $msg = sprintf('Someting went wrong!(%s)', $exception->getMessage());
        return new Response($msg, $exception->getStatusCode());
    }
}