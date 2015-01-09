<?php

namespace Acme\MainBundle\Controller;

use Acme\MainBundle\Entity\Users;
use Acme\MainBundle\Lib\Auth;
use Acme\VkBundle\Entity\VkParsingTasks;
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
     * @Route("/logout", name = "secured.logout")
     */
    public function logoutAction(Request $request) {
        /** @var Auth $auth */
        $auth = $this->container->get('acme_main.auth');
        $auth->logout();

        return $this->redirectToRoute("landingpage");
    }

    /**
     *  @Route("/delete", name = "secured.delete")
     *  @NeedAuth()
     */
    public function deleteAction(Request $request) {
        /** @var Auth $auth */
        $auth = $this->container->get('acme_main.auth');
        $repository = $this->getDoctrine()->getRepository('AcmeMainBundle:Users');
        $user = $repository->find($auth->getId());
        $repository = $this->getDoctrine()->getRepository('AcmeVkBundle:VkUsers');
        $vkUser = $repository->find($auth->getVkId());
        if (!is_null($vkUser) && !is_null($user)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vkUser);
            $em->remove($user);
            $em->flush();
        }
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
            $request->get('type'),
            $request->get('vk_user_id'),
            $request->get('token'),
            $request->get('expires_in'),
            $request->get('email')
        );

        $task1 = new VkParsingTasks();
        $task1->setVkUser($auth->getVkUser())->setLevel(0);
        $task2 = new VkParsingTasks();
        $task2->setVkUser($auth->getVkUser())->setLevel(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($task1);
        $em->persist($task2);
        $em->flush();


        return $this->redirectToRoute('secured.index');
    }


}