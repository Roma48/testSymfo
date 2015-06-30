<?php

namespace CTO\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CtoClientRepository extends EntityRepository
{
    public function clientFilter($filterData)
    {
        $qb = $this->createQueryBuilder('u');

        if (array_key_exists('firstName', $filterData)) {
            $qb->andWhere('u.firstName like :firstname')
                ->setParameter('firstname', '%' . $filterData['firstName'] . '%');
        }
        if (array_key_exists('lastName', $filterData)) {
            $qb->andWhere('u.lastName like :lastname')
                ->setParameter('lastname', '%' . $filterData['lastName'] . '%');
        }

        return $qb->getQuery()->getResult();
    }

    public function listClientwWithSorting()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u From CTOAppBundle:CtoClient u');
    }
}
