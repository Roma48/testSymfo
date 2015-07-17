<?php

namespace CTO\AppBundle\Entity\Repository;

use CTO\AppBundle\Entity\CtoUser;
use DateTime;
use Doctrine\ORM\EntityRepository;

class CarJobRepository extends EntityRepository
{
    public function listJobsWithSortings(CtoUser $user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT j From CTOAppBundle:CarJob j left JOIN j.client cc left JOIN j.car jcar left JOIN jcar.model m WHERE cc.cto = :ctoUser ORDER by j.jobDate DESC ')->setParameter('ctoUser', $user);
    }

    public function jobsFilter($filterData, CtoUser $user)
    {
        $qb = $this->createQueryBuilder('j')
            ->join('j.client', 'cl')
            ->andWhere('cl.cto = :ctoUser')
            ->setParameter('ctoUser', $user);

        if (array_key_exists('fullName', $filterData)) {
            $qb->andWhere('cl.fullName like :fullName')
                ->setParameter('fullName', '%' . $filterData['fullName'] . '%');
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
