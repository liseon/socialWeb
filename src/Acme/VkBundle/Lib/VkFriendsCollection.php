<?php

namespace Acme\VkBundle\Lib;

use Acme\MainBundle\Lib\Abstracts\CollectionAbstract;
use Acme\MainBundle\Lib\Interfaces\FriendsCollectionInterface;

/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 28.11.2014
 * Time: 23:25
 */

class VkFriendsCollection extends CollectionAbstract implements FriendsCollectionInterface
{
    /**
     * @param array|false $rows
     * @param VkFriendsCollection|null $clean
     */
    public function __construct($rows, $clean = null) {
        if (!is_array($rows)) {
            return;
        }
        //Очистим от друзей, которые итак уже являются моими
        if ($clean instanceof VkFriendsCollection) {
            $clean = $clean->getRows();
            $rows = array_filter($rows, function($value) use($clean) {
                   return !in_array($value, $clean);
                });
        }
        $this->rows = $rows;
    }

    public function getId() {

        return $this->getCurrent();
    }

    public function getName(){
    }

    public function getMutualFriendId(){
    }

    public function prepareEntities() {
        return $this;
    }
}