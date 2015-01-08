<?php

namespace Acme\MainBundle\Controller;

use Acme\MainBundle\Lib\Auth;
use Acme\VkBundle\Lib\VkApi;
use Acme\MainBundle\Annotation\NeedAuth;

use AppBundle\Lib\CookiesHelper;
use AppBundle\Lib\SessionHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/secured")
 * Class SecuredController
 * @package Acme\MainBundle\Controller
 */
class SecuredController extends Controller
{
    /**
     * @Route("/", name = "secured.index")
     * @NeedAuth()
     */
    public function indexAction(Request $request) {
        /** @var Auth $auth */
        $auth = $this->container->get('acme_main.auth');
        varlog("Auth result:", $auth->check());

//        return $this->render('AcmeMainBundle:Secured:');
        return $this->render('secure/index.html.twig');
    }

    /**
     * @Route("/logout", name = "secure.logout")
     */
    public function logoutAction(Request $request) {
        /** @var Auth $auth */
        $auth = $this->container->get('acme_main.auth');
        $auth->logout();

        return $this->redirectToRoute("landingpage");
    }

    /**
     * Нельзя включать это в роутинг! Доступ сюда только через forward после получения токена от соц. сети!
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        /** @var Auth $auth */
        $auth = $this->container->get('acme_main.auth');
        $auth->setAuth(
            $request->get('user_id'),
            $request->get('vk_user_id'),
            $request->get('type'),
            $request->get('token')
        );

        return $this->redirectToRoute('secured.index');
    }

   /* private function check(Request $request) {
        if (Auth::check($this->getDoctrine())) {
            return true;
        }
        //try Re
        if ($type = $request->cookies->get(Auth::COOKIE_RE)) {
            switch ($type) {
                case Auth::TYPE_VK:
                    return $this->redirect(VkApi::getAuthUrl());
                    break;
            }
        }

        return $this->redirectToRoute("landingpage");
    }*/


}