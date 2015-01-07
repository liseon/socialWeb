<?php

namespace AppBundle\Lib;

use AppBundle\Patterns\Singleton;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionHelper extends Singleton
{
    /** @var  Session */
    private $session;

    protected function __construct() {
        $this->session  = new Session();
        $this->session ->start();
    }

    public static function get($name) {
        return self::getInstance()->session->get($name);
    }

    public static function set($name, $value) {
        return self::getInstance()->session->set($name, $value);
    }

    public static function remove($name) {
        return self::getInstance()->session->remove($name);
    }
}