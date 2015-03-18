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

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AcmeMainBundle:Users');
        $user = $repository->find($auth->getId());
        !is_null($user) && $em->remove($user);

        $repository = $this->getDoctrine()->getRepository('AcmeVkBundle:VkUsers');
        $vkUser = $repository->findOneByVkId($auth->getVkId());
        !is_null($vkUser) && $em->remove($vkUser);

        $repository = $this->getDoctrine()->getRepository('AcmeMainBundle:Subscriptions');
        $subscription = $repository->findOneByUser($auth->getId());
        !is_null($user) && $em->remove($subscription);

        $repository = $this->getDoctrine()->getRepository('AcmeVkBundle:VkParsingTasks');
        $tasks = $repository->findByVkUser($auth->getVkId());
        foreach ($tasks as $task) {
            !is_null($task) && $em->remove($task);
        }

        $em->flush();
        
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
        $subscription->setEmail($email)->setUser($auth->getId());

        $form = $this->createFormBuilder($subscription)
            ->add('email', 'email')
            ->add('subscr', 'submit', ['label' => 'Прислать уведомление'])
            ->getForm();
        $formUnsubscribe = $this->createFormBuilder($subscription)
            ->add('not_subscr', 'submit', ['label' => 'Отписаться от рассылок'])
            ->getForm();

        $form->handleRequest($request);
        $formUnsubscribe->handleRequest($request);

        if (!$formUnsubscribe->isValid() && !$form->isValid()) {
            return ['form' => $form->createView(), 'formUnsubscribe' => $formUnsubscribe->createView()];
        }
        $repository = $this->getDoctrine()->getRepository("AcmeMainBundle:Subscriptions");
        $subscription = $repository->findOneByUser($auth->getId());

        if ($formUnsubscribe->isValid()) {
            if (!($subscription instanceof Subscriptions)) {

                return $this->redirectToRoute('secured.index');
            }
            $subscription->setIsActive(false);
        } elseif ($form->isValid()) {
            if (!($subscription instanceof Subscriptions)) {
                $subscription = new Subscriptions();
                $subscription->setUser($auth->getId());
            }
            $subscription->setEmail($email);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($subscription);
        $em->flush();

        return $this->redirectToRoute('secured.index');
    }

    /**
     * @Route("/create_tasks", name = "secured.create_tasks")
     * @NeedAuth()
     */
    public function actionCreateTasks(Request $request) {
        $auth = $this->getAuth();
        $vkUser = $auth->getVkUser();

        $repository = $this->getDoctrine()->getRepository("AcmeVkBundle:VkParsingTasks");
        if (!$repository->findOneByVkUser($vkUser->getVkId())) {
            $task1 = new VkParsingTasks();
            $task1->setVkUser($vkUser->getVkId())->setLevel(0);
            $task2 = new VkParsingTasks();
            $task2->setVkUser($vkUser->getVkId())->setLevel(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task1);
            $em->persist($task2);
            $em->flush();
        }

        $repository = $this->getDoctrine()->getRepository("AcmeMainBundle:Subscriptions");
        $subscription = $repository->findOneByUser($auth->getId());
        /** @var Subscriptions $subscription */
        if ($subscription && $subscription->getIsActive()) {
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