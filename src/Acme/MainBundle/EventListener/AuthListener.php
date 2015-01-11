<?php

namespace Acme\MainBundle\EventListener;

use Acme\MainBundle\Exception\NotAuthException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Acme\MainBundle\Annotation\NeedAuth;
use Acme\MainBundle\Lib\Auth;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class AuthListener
{
    /** @var  Reader */
    private $reader;

    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    /**
     * Check Auth!
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event) {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        $annotation = $this->reader->getMethodAnnotation($method, \Acme\MainBundle\Annotation\NeedAuth::class);
        if (is_null($annotation)) {
            return;
        }

        /** @var Auth $auth */
        $auth = $controller[0]->get('acme_main.auth');
        if (!$auth->check()) {
            varlog($event->getRequest()->getSession()->get('type'));
            varlog($event->getRequest()->getSession()->get('vk_user'));
            die("FUck!");
            //throw new NotAuthException();
        }
    }
}