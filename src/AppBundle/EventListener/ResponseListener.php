<?php

namespace AppBundle\EventListener;

use AppBundle\Lib\CookiesHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ResponseListener
{
    /**
     * Save cookies!
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event) {
        if (!$event->isMasterRequest()) {
            return;
        }
        $response = $event->getResponse();
        $event->setResponse(CookiesHelper::saveAll($response));
    }
}