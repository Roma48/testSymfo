<?php

namespace CTO\AppBundle\Entity\Repository;

use CTO\AppBundle\Entity\CtoUser;
use Doctrine\ORM\EntityRepository;

class CtoClientRepository extends EntityRepository
{
    public function clientFilter($filterData, CtoUser $user)
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.cto = :ctoUser')
            ->setParameter('ctoUser', $user);

        if (array_key_exists('firstName', $filterData)) {
            $qb->andWhere('u.firstName like :firstname')
                ->setParameter('firstname', '%' . $filterData['firstName'] . '%');
        }
        if (array_key_exists('lastName', $filterData)) {
            $qb->andWhere('u.lastName like :lastname')
                ->setParameter('lastname', '%' . $filterData['lastName'] . '%');
        }
        $qb
            ->orderBy('u.lastVisitDate', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function listClientwWithSorting(CtoUser $user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u From CTOAppBundle:CtoClient u WHERE u.cto = :ctoUser order by u.lastVisitDate ASC ')->setParameter('ctoUser', $user);
    }
}
