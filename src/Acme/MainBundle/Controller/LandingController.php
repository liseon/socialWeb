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
    public function indexAction(Request $request)
    {
        if (Auth::check($this->getDoctrine())) {
            return $this->redirectToRoute("secure.index");
        }

        varlog("hello!");

        return $this->render('landing/index.html.twig', [
            'vk_auth' => VkApi::getAuthUrl(),
        ]);
    }
}