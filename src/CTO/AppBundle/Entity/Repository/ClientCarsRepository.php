<?php

namespace CTO\AppBundle\Entity\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class ClientCarsRepository extends EntityRepository
{
    public function listAllCarsWithSorting()
    {
        return $this->getEntityManager()
            ->createQuery('select car from CTOAppBundle:ClientCar car JOIN car.ctoClient cl JOIN car.model mod ORDER BY cl.lastVisitDate ASC');
    }

    public function carFilter($filterData) {
        $qb = $this->createQueryBuilder('c');

        if (array_key_exists('fullName', $filterData)) {
            $qb->join('c.ctoClient', 'cl')
                ->andWhere('cl.fullName like :fullName')
                ->setParameter('fullName', '%' . $filterData['fullName'] . '%');
        }
        if (array_key_exists('model', $filterData)) {
            $qb->join('c.model', 'mo')
                ->andWhere('mo = :model')
                ->setParameter('model', $filterData['model']);
        }
        if (array_key_exists('dateFrom', $filterData)) {
            $qb->join('c.ctoClient', 'cl')
                ->andWhere('cl.lastVisitDate >= :dateFrom')
                ->setParameter('dateFrom', new DateTime($filterData['dateFrom']));
        }
        if (array_key_exists('dateTo', $filterData)) {
            $qb->join('c.ctoClient', 'cl')
                ->andWhere('cl.lastVisitDate <= :dateTo')
                ->setParameter('dateTo', new DateTime($filterData['dateTo']));
        }

        return $qb->getQuery()->getResult();
    }
}
