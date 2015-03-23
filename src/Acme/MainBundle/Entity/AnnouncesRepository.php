<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AnnouncesRepository extends EntityRepository
{
    public function findAllOrderedByName($limit = 100, $offset = 0) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p, c FROM AcmeMainBundle:Announces a
                JOIN p.category c
                WHERE p.id = :id'
            )->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}