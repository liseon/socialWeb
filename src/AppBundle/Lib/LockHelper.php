<?php

namespace AppBundle\Lib;
use AppBundle\Patterns\Singleton;

/**
 * Статический помощник для блокировки cli-скриптов
 *
 */

class LockHelper extends Singleton
{

    const PATH = 'app/locks/';

    private $fname;

    private $fp;

    public static function start($name) {
        self::getInstance()->lock($name);
    }

    public static function stop() {
        self::getInstance()->unlock();
    }

    protected function lock($name) {
        $this->fname = self::PATH . str_replace(":", "_", $name) . '.lock';
        $ex = file_exists($this->fname);
        $this->fp = fopen($this->fname, 'w');
        if (!flock($this->fp, LOCK_EX | LOCK_NB)) {
            varlog("Already runned!");
            exit(-1);
        }
        $ex && varlog("Last run were failed!");
    }

    protected function unlock() {
        if (!$this->fname) {
            return false;
        }
        flock($this->fp, LOCK_UN);
        unlink($this->fname);
        exit(0);
    }


}