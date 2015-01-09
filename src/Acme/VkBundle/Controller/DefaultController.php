<?php

namespace Acme\VkBundle\Controller;

use Acme\MainBundle\Lib\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\VkBundle\Lib\VkApi;


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
    public function callbackAction(Request $request) {
        $result = VkApi::getInstance()->getNewAccessToken($request->get(VkApi::PARAM_CODE));
        if (!is_array($result) || !isset($result['access_token'])) {

            return $this->redirectToRoute('landingpage', array('error' => 'wrong_result'));
        }

        //авторизуем
        return $this->forward("AcmeMainBundle:Secured:login", [
            'type' => Auth::TYPE_VK,
            'vk_user_id' => $result['user_id'],
            'token' => $result['access_token'],
            'expires_in' => $result['expires_in'],
            'email' => isset($result['email']) ? $result['email'] : null,
        ]);
    }

    /**
     * @Template()
     */
    public function authLinkAction() {
        return [
            'vk_auth' => VkApi::getAuthUrl(),
        ];
    }
}
