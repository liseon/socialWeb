<?php

namespace Acme\MainBundle\Controller;

use Acme\MainBundle\Lib\Auth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Acme\VkBundle\Lib\VkApi;


class LandingController extends Controller
{
    /**
     * @Route("/", name="landingpage")
     * @param Request $request
     *
     * @return Response A Response instance
     */
    public function indexAction(Request $request) {
        /** @var Auth $auth */
        $auth = $this->container->get('acme_main.auth');
        if ($auth->check()) {
            return $this->redirectToRoute("secured.index");
        }

        return $this->render('landing/index.html.twig');
    }
}