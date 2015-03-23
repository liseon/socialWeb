<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

class AnnouncesRepository extends EntityRepository
{
    public function findForUserOrderByCreated($uid, $limit = 100, $offset = 0) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p, at FROM AcmeMainBundle:Announces p
                LEFT JOIN AcmeVkBundle:VkAttachments at WHERE at.announce = p.id
                WHERE p.forUserId = :uid
                ORDER BY p.postCreatedAt'
            )
            ->setParameter('uid', $uid)
            ->setMaxResults($limit)
            ->setFirstResult($offset);
        /** @var Query $query */

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}