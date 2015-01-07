<?php

namespace Acme\MainBundle\Controller;

use Acme\MainBundle\Lib\Auth;
use Acme\VkBundle\Lib\VkApi;
use AppBundle\Lib\CookiesHelper;
use AppBundle\Lib\SessionHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/secure")
 * Class SecureController
 * @package Acme\MainBundle\Controller
 */
class SecureController extends Controller
{
    /**
     * @Route("/", name = "secure.index")
     */
    public function indexAction(Request $request) {
        $this->check($request);

        return $this->render('secure/index.html.twig');
    }

    /**
     * @Route("/logout", name = "secure.logout")
     */
    public function logoutAction(Request $request) {
        Auth::logout();

        return CookiesHelper::saveAll($this->redirectToRoute("landingpage"));
    }

    private function check(Request $request) {
        if (Auth::check($this->getDoctrine())) {
            return true;
        }
        //try Re
        if ($type = $request->cookies->get(Auth::COOKIE_RE)) {
            switch ($type) {
                case Auth::TYPE_VK:
                    $this->redirect(VkApi::getAuthUrl());
                    break;
            }
        }

        $this->redirectToRoute("landingpage");
    }


}