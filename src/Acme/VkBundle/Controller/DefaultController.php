<?php

namespace Acme\VkBundle\Controller;

use Acme\MainBundle\Lib\Auth;
use AppBundle\Lib\CookiesHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;

use Acme\VkBundle\Lib\VkApi;
use Acme\VkBundle\Entity\VkUsers;
use Acme\MainBundle\Entity\Users;


/**
 * @Route("/vk/")
 * Class DefaultController
 * @package Acme\VkBundle\Controller
 *
 */
class DefaultController extends Controller
{
    /**
     * @Route("callback/", name = "vk.callback")
     */
    public function callbackAction(Request $request)
    {
        $result = VkApi::getInstance()->getNewAccessToken($request->get(VkApi::PARAM_CODE));
        if (!is_array($result) || !isset($result['access_token'])) {

            return $this->redirectToRoute('landingpage', array('error' => 'wrong_result'));
        }
        $repository = $this->getDoctrine()->getRepository('AcmeVkBundle:VkUsers');
        /** @var VkUsers $vkUser */
        $vkUser = $repository->find($result['user_id']);
        //Новый пользователь
        if (!$vkUser) {
            $mainUser = new Users();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mainUser);
            $vkUser = new VkUsers();
            $vkUser->setVkId($result['user_id'])->setToken($result['access_token'])->setUser($mainUser);
            $vkUser->setTokenExpiresAt(new \DateTime(date_create(time() + $result['expires_in'])));
            isset($result['email']) && $vkUser->setEmail($result['email']);
            $em->persist($vkUser);
            $em->flush();
        }
        //авторизуем
        Auth::setAuth($vkUser->getUser()->getId(), $result['user_id'], Auth::TYPE_VK, $result['access_token']);

        return CookiesHelper::saveAll($this->redirectToRoute('secure.index'));
    }
}
