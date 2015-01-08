<?php

namespace Acme\MainBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Acme\MainBundle\Exception\NotAuthException;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        if ($exception instanceof NotAuthException) {
            $response = new RedirectResponse('/');
            $event->setResponse($response);
        }
    }
}