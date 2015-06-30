<?php

namespace CTO\AppBundle\Entity\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class CarJobRepository extends EntityRepository
{
    public function listJobsWithSortings()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT j From CTOAppBundle:CarJob j JOIN j.client cc JOIN  j.jobCategory jc JOIN j.car jcar JOIN jcar.model m');
    }

    public function jobsFilter($filterData)
    {
        $qb = $this->createQueryBuilder('j');

        if (array_key_exists('fullName', $filterData)) {
            $qb->join('j.client', 'cl')
                ->andWhere('cl.fullName like :fullName')
                ->setParameter('fullName', '%' . $filterData['fullName'] . '%');
        }
        if (array_key_exists('jobCategory', $filterData)) {
            $qb->join('j.jobCategory', 'jc')
                ->andWhere('jc = :jobCategory')
                ->setParameter('jobCategory', $filterData['jobCategory']);
        }
        if (array_key_exists('dateFrom', $filterData)) {
            $qb->andWhere('j.jobDate >= :dateFrom')
                ->setParameter('dateFrom', new DateTime($filterData['dateFrom']));
        }
        if (array_key_exists('dateTo', $filterData)) {
            $qb->andWhere('j.jobDate <= :dateTo')
                ->setParameter('dateTo', new DateTime($filterData['dateTo']));
        }

        return $qb->getQuery()->getResult();
    }
}
