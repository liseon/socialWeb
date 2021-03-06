<?php

namespace Acme\MainBundle\Lib;

use Acme\MainBundle\Entity\Users;
use Acme\VkBundle\Entity\VkUsers;
use AppBundle\Lib\CookiesHelper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;


class Auth
{
    /** @var  VkUsers */
    private $vkUser;

    /** @var  string */
    private $type;

    /** @var  Session */
    private $doctrine;

    /** @var  Session */
    private $session;

    const COOKIE_PARAM = 'auth';

    const COOKIE_RE = 're';

    const TYPE_VK = 'vk';


    public function __construct(Registry $doctrine, Session $session) {
        $this->doctrine = $doctrine;
        $this->session = $session;
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $this->init();
    }

    /**
     * @return bool
     */
    public function check() {

        return $this->vkUser instanceof VkUsers;
    }

    public function getId() {
        if (!$this->check()) {
            return false;
        }
        return $this->vkUser->getUser()->getId();
    }

    public function getVkId() {
        if (!$this->check()) {
            return false;
        }
        return $this->vkUser->getVkId();
    }

    /**
     * @return VkUsers|bool
     */
    public function getVkUser() {
        if (!$this->check()) {
            return false;
        }
        return $this->vkUser;
    }

    public function getVkEmail() {
        if (!$this->check()) {
            return false;
        }
        return $this->vkUser->getEmail();
    }

    public function getEmail() {
        if (!$this->check()) {
            return false;
        }
        return $this->vkUser->getUser()->getEmail();
    }

    /**
     * @todo type пока не проверяется!
     * Потом $this->vkUser станет типовым пользователем абстрактной социальной сети,
     * а сама сеть будет передаваться в поле type
     *
     * @param $type
     * @param $vkUserId
     * @param $token
     * @param $expiresIn
     * @param null|string $email
     */
    public function setAuth($type, $vkUserId, $token, $expiresIn, $email = null) {
        $this->type = $type;
        $em = $this->doctrine->getManager();

        $repository = $this->doctrine->getRepository('AcmeVkBundle:VkUsers');
        /** @var VkUsers $vkUser */
        $vkUser = $repository->findOneBy(['vkId' => $vkUserId]);
        //Новый пользователь
        if (!$vkUser) {
            $mainUser = new Users();
            $em->persist($mainUser);
            $vkUser = new VkUsers();
            $vkUser->setVkId($vkUserId)->setUser($mainUser)->setEmail($email);
        }
        //Обновить токен!
        $vkUser->setToken($token)->setTokenExpiresAt(date_create()->setTimestamp(time() + $expiresIn));

        $em->persist($vkUser);
        $em->flush();

        $this->vkUser = $vkUser;

        $this->saveToSession($token);
    }

    public function logout() {
        $this->vkUser = false;
        $this->type = false;

        $this->session->remove('type');
        $this->session->remove('vk_user');

        CookiesHelper::setCookie(self::COOKIE_PARAM, 0, -1);
        CookiesHelper::setCookie(self::COOKIE_RE, 0, -1);
    }

    /**
     * Проводит инициализацию параметров авторизации.
     * Вернет true  в случае успешной инициализации.
     * Вернет false, если нет данных для авторизации.
     *
     * @return bool|string
     */
    private function init() {
        //already
        if ($this->check()) {
            return true;
        }
        //Сессия протухла, проверим в cookies
        if (!$this->session->get('type')) {
            $request = Request::createFromGlobals();
            //Нужна переавтоизация
            $json = $request->cookies->get(self::COOKIE_PARAM);
            if ($json && is_array($params = json_decode($json, true))) {
                return $this->reAuth($params);
            }

            return false;
        }
        $this->vkUser = $this->session->get('vk_user');
        $this->type = $this->session->get('type');

        return true;
    }

    private function reAuth($params) {
        if (
            !isset($params['type'])
            || !isset($params['vk_user_id'])
            || !isset($params['token'])
            || !($params['vk_user_id'] > 0)
        ) {
            return false;
        }

        //only vk supported
        if ($params['type'] == self::TYPE_VK) {
            $repository = $this->doctrine->getRepository('AcmeVkBundle:VkUsers');
            $vkUser = $repository->find($params['vk_user_id']);
            /** @var VkUsers $vkUser */
            if ($vkUser && $vkUser->getToken() == $params['token']) {
                $this->type = self::TYPE_VK;
                $this->vkUser = $vkUser;

                $this->saveToSession($params['token']);

                return true;
            }
        }

        return false;
    }

    private function saveToSession($token) {
        $this->session->set('type', $this->type);
        $this->session->set('vk_user', $this->vkUser);

        $cookie = [
            'type' => self::TYPE_VK,
            'vk_user_id' => $this->vkUser->getVkId(),
            'token' => $token,
        ];

        CookiesHelper::setCookie(self::COOKIE_PARAM, json_encode($cookie), CookiesHelper::DAY);
        CookiesHelper::setCookie(self::COOKIE_RE, self::TYPE_VK, CookiesHelper::YEAR);
    }
}