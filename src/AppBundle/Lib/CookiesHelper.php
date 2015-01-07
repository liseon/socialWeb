<?php

namespace AppBundle\Lib;

use AppBundle\Patterns\Singleton;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CookiesHelper extends Singleton
{

    private $cookies = [];

    const YEAR = 31536000; // year

    const DAY = 86400; //day

    public static function setCookie($name, $value, $expire = self::YEAR) {
        self::getInstance()->cookies[$name] = new Cookie($name, $value, time() +  $expire);
    }

    /**
     * @param Response $response
     * @return Response
     */
    public static function saveAll(Response $response) {
        $cookies = self::getInstance()->cookies;
        foreach ($cookies as $cookie) {
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}