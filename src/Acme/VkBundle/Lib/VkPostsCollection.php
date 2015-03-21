<?php

namespace Acme\VkBundle\Lib;

use Acme\MainBundle\Lib\Interfaces\PostsCollectionInterface;
use Acme\MainBundle\Lib\Abstracts\CollectionAbstract;

/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 27.11.2014
 * Time: 21:02
 */

class VkPostsCollection extends CollectionAbstract implements PostsCollectionInterface
{
    public function getPostTime() {

        return $this->getProperty('created');
    }

    public function getPostText() {

        return $this->getProperty('text') . $this->getProperty('copy_text');
    }

    public function getPostId() {

        return $this->getProperty('id');
    }

    public function getOwnerId() {

        return $this->getProperty('to_id');
    }
}