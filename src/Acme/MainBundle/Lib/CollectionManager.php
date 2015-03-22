<?php

namespace Acme\MainBundle\Lib;

use Acme\MainBundle\Lib\Abstracts\CollectionAbstract;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

class CollectionManager
{
    /** @var EntityManager  */
    private $em;

    public function __construct(Registry $doctrine) {
        $this->em = $doctrine->getManager();
    }

    /**
     * @param CollectionAbstract $collection
     */
    public function save(CollectionAbstract $collection) {
        foreach ($collection->getEntities() as $ent) {
            $this->em->persist($ent);
        }
        $this->em->flush();
    }
}