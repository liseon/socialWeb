<?php

namespace Acme\MainBundle\Lib;

use Acme\VkBundle\Entity\VkUsers;
use AppBundle\Lib\CookiesHelper;
use AppBundle\Lib\SessionHelper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;


class Auth
{
    /** @var  bool */
    private $isAuth;

    /** @var  int */
    private $userId;

    /** @var  int */
    private $vkUserId;

    /** @var  string */
    private $type;

    /** @var  Registry */
    private $doctrine;

    const COOKIE_PARAM = 'auth';

    const COOKIE_RE = 're';

    const TYPE_VK = 'vk';


    public function __construct(Registry $doctrine) {
        $this->doctrine = $doctrine;
        $this->isAuth = $this->init();
    }

    /**
     * @return bool
     */
    public function check() {

        return (bool)$this->isAuth;
    }

    public function getId() {
        return $this->userId;
    }

    public function getVkId() {
        return $this->vkUserId;
    }

    public function setAuth($userId, $vkUserId, $type, $token) {
        $this->isAuth = true;
        $this->userId = $userId;
        $this->vkUserId = $vkUserId;
        $this->type = $type;

        $this->saveToSession($token);
    }

    public function logout() {
        $this->isAuth = false;
        $this->userId = false;
        $this->vkUserId = false;
        $this->type = false;

        SessionHelper::remove('type');
        SessionHelper::remove('vk_user_id');
        SessionHelper::remove('user_id');

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
        if ($this->isAuth) {
            return true;
        }
        //Сессия протухла, проверим в cookies
        if (!SessionHelper::get('type')) {
            $request = Request::createFromGlobals();
            //Нужна переавтоизация
            $json = $request->cookies->get(self::COOKIE_PARAM);
            if ($json && is_array($params = json_decode($json, true))) {
                return $this->reAuth($params);
            }

            return false;
        }
        $this->userId = SessionHelper::get('user_id');
        $this->vkUserId = SessionHelper::get('vk_user_id');
        $this->type = SessionHelper::get('type');

        return true;
    }

    private function reAuth($params) {
        if (
            !isset($params['type'])
            || !isset($params['user_id'])
            || !isset($params['token'])
            || !($params['user_id'] > 0)
        ) {
            return false;
        }

        //only vk supported
        if ($params['type'] == self::TYPE_VK) {
            $repository = $this->doctrine->getRepository('AcmeVkBundle:VkUsers');
            $vkUser = $repository->findOneBy(["user" => $params['user_id']]);
            /** @var VkUsers $vkUser */
            if ($vkUser && $vkUser->getToken() == $params['token']) {
                $this->type = self::TYPE_VK;
                $this->userId = $params['user_id'];
                $this->vkUserId = $vkUser->getVkId();

                $this->saveToSession($params['token']);

                return true;
            }
        }

        return false;
    }

    private function saveToSession($token) {
        SessionHelper::set('type', $this->type);
        SessionHelper::set('vk_user_id', $this->vkUserId);
        SessionHelper::set('user_id', $this->userId);

        $cookie = [
            'type' => self::TYPE_VK,
            'user_id' => $this->userId,
            'token' => $token,
        ];

        CookiesHelper::setCookie(self::COOKIE_PARAM, json_encode($cookie), CookiesHelper::DAY);
        CookiesHelper::setCookie(self::COOKIE_RE, self::TYPE_VK, CookiesHelper::YEAR);
    }
}