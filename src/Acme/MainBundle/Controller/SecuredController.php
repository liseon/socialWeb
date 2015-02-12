<?php

namespace Acme\MainBundle\Controller;

use Acme\MainBundle\AcmeMainBundle;
use Acme\MainBundle\Entity\Subscriptions;
use Acme\MainBundle\Lib\Auth;
use Acme\VkBundle\Entity\VkParsingTasks;
use Acme\MainBundle\Annotation\NeedAuth;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Template()
     */
    public function indexAction(Request $request) {

        return [];
        return $this->render('secure/index.html.twig');
    }

    /**
     * @Route("/logout", name = "secured.logout")
     */
    public function logoutAction(Request $request) {
        $this->getAuth()->logout();

        return $this->redirectToRoute("landingpage");
    }

    /**
     *  @Route("/delete", name = "secured.delete")
     *  @NeedAuth()
     */
    public function deleteAction(Request $request) {
        $auth = $this->getAuth();
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
        $this->getAuth()->setAuth(
            $request->get('type'),
            $request->get('vk_user_id'),
            $request->get('token'),
            $request->get('expires_in'),
            $request->get('email')
        );

        return $this->redirectToRoute('secured.create_tasks');
    }


    /**
     * @Route("/subscribe", name = "secured.subscribe")
     * @Template()
     * @NeedAuth()
     */
    public function subscriptionAction(Request $request) {
        $auth = $this->getAuth();
        $subscription = new Subscriptions();
        $email = $auth->getVkEmail() ? : $auth->getEmail() ? : '';
        $subscription->setEmail($email)->setUser($auth->getVkUser()->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($subscription);
        $em->flush();

       /* $form = $this->createFormBuilder($subscription)
            ->add('email', 'email')
            ->add('subscr', 'submit', ['label' => 'Прислать уведомление'])
            ->getForm();
        $formUnsubscribe = $this->createFormBuilder($subscription)
            ->add('not_subscr', 'submit', ['label' => 'Отписаться от рассылок'])
            ->getForm();

        $form->handleRequest($request);
        $formUnsubscribe->handleRequest($request);
        if ($form->isValid() || $formUnsubscribe->isValid()) {
            if ($formUnsubscribe->isValid()) {
                $repository = $this->getDoctrine()->getRepository("AcmeMainBundle:Subscriptions");
                $subscr = $repository->findOneByUser($auth->getId());
                if ($subscr) {
                    $subscription = $subscr;
                }
            } else {
                $subscription = new Subscriptions();
                $subscription->setEmail($email);
                $subscription->setUser($auth->getVkUser()->getUser());
            }

            varlog($auth->getVkUser()->getUser()->getId());
            //die();

            $formUnsubscribe->isValid() &&  $subscription->setIsActive(false);
            $form->isValid() && $subscription->setEmail($form->get('email')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($subscription);
            $em->flush();

            return $this->redirectToRoute('secured.index');
        }

        return ['form' => $form->createView(), 'formUnsubscribe' => $formUnsubscribe->createView()];*/
        die();
    }

    /**
     * @Route("/create_tasks", name = "secured.create_tasks")
     * @NeedAuth()
     */
    public function actionCreateTasks(Request $request) {
        $auth = $this->getAuth();
        $vkUser = $auth->getVkUser();

        $repository = $this->getDoctrine()->getRepository("AcmeVkBundle:VkParsingTasks");
        if (!$repository->findOneByVkUser($vkUser)) {
            $task1 = new VkParsingTasks();
            $task1->setVkUser($vkUser)->setLevel(0);
            $task2 = new VkParsingTasks();
            $task2->setVkUser($vkUser)->setLevel(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($vkUser);
            $em->persist($task1);
            $em->persist($task2);
            $em->flush();
        }

        $repository = $this->getDoctrine()->getRepository("AcmeMainBundle:Subscriptions");
        if ($repository->findOneByUser($auth->getId())) {
            return $this->redirectToRoute('secured.index');
        }

        return $this->redirectToRoute('secured.subscribe');
    }

    /**
     * @return Auth
     */
    private function getAuth() {
        return $this->container->get('acme_main.auth');
    }


}