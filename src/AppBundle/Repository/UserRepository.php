<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function remove($me, $friends)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.friends IN (:friends)')
            //->setParameter('me', $me)
            ->setParameter('friends', $friends)
            ->getQuery();
        return $qb->getResult();
    }
}