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

    public function countForMonth($start, $end, CtoUser $user)
    {
        return $this->createQueryBuilder('j')
            ->select('count(j) as jobs')
            ->join('j.client', 'cl')
            ->andWhere('cl.cto = :ctoUser')
            ->setParameter('ctoUser', $user)
            ->andWhere('j.jobDate >= :start')
            ->setParameter('start', $start)
            ->andWhere('j.jobDate <= :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleResult();
    }

    public function countDistinctClientCarsForMonth($start, $end, CtoUser $user)
    {
        return $this->createQueryBuilder('j')
            ->select('count(distinct car) as cars')
            ->join('j.client', 'cl')
            ->join('j.car', 'car')
            ->andWhere('cl.cto = :ctoUser')
            ->setParameter('ctoUser', $user)
            ->andWhere('j.jobDate >= :start')
            ->setParameter('start', $start)
            ->andWhere('j.jobDate <= :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleResult();
    }

    public function totalSalaryForMonth($start, $end, CtoUser $user)
    {
        return $this->createQueryBuilder('j')
            ->select('sum(j.totalCost) - sum(j.totalSpend) as money')
            ->join('j.client', 'cl')
            ->andWhere('cl.cto = :ctoUser')
            ->setParameter('ctoUser', $user)
            ->andWhere('j.jobDate >= :start')
            ->setParameter('start', $start)
            ->andWhere('j.jobDate <= :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleResult();
    }

    public function totalFinancialReportForMonth($start, $end, CtoUser $user)
    {
        return $this->createQueryBuilder('j')
            ->select('sum(j.totalCost) - sum(j.totalSpend) as money, count(distinct car) as cars, count(j) as jobs')
            ->join('j.client', 'cl')
            ->join('j.car', 'car')
            ->andWhere('cl.cto = :ctoUser')
            ->setParameter('ctoUser', $user)
            ->andWhere('j.jobDate >= :start')
            ->setParameter('start', $start)
            ->andWhere('j.jobDate <= :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleResult();
    }

    public function getOneJobByCTOUser(CtoUser $user, $order)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->join('j.client', 'cl')
            ->andWhere('cl.cto = :ctoUser')
            ->setParameter('ctoUser', $user)
            ->orderBy('j.jobDate', $order)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
