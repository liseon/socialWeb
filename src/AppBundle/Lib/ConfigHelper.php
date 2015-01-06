<?php

namespace AppBundle\Lib;

use Symfony\Component\Yaml\Yaml;

class ConfigHelper
{
    private static $config = [];

    public static function get($name, $value = false, $default = null) {
        if (!isset(self::$config[$name])) {
            self::$config[$name] = Yaml::parse(__DIR__.'/../../../app/config/' . (string)$name . '.yml');
        }

        if (!is_array(self::$config[$name]) || ($value && !isset(self::$config[$name][$value]))) {
            return $default;
        }


        return $value ? self::$config[$name][$value] : self::$config[$name];
    }
}