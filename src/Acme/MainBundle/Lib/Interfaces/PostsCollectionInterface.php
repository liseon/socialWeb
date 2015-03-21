<?php

namespace Acme\MainBundle\Lib\Interfaces;

/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 28.11.2014
 * Time: 23:03
 */

interface PostsCollectionInterface
{
    public function getPostTime();

    public function getPostText();

    public function getPostId();

    public function getOwnerId();
}