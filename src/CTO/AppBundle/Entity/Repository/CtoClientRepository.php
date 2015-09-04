<?php

namespace CTO\AppBundle\Entity\Repository;

use CTO\AppBundle\Entity\CtoUser;
use DateTime;
use Doctrine\ORM\EntityRepository;

class CtoClientRepository extends EntityRepository
{
    public function clientFilter($filterData, CtoUser $user)
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.cto = :ctoUser')
            ->setParameter('ctoUser', $user);

        if (array_key_exists('fullName', $filterData)) {
            $qb->andWhere('u.fullName like :fullname')
                ->setParameter('fullname', '%' . $filterData['fullName'] . '%');
        }
        if (array_key_exists('dateFrom', $filterData)) {
            $qb->andWhere('u.lastVisitDate >= :dateFrom')
                ->setParameter('dateFrom', new DateTime($filterData['dateFrom']));
        }
        if (array_key_exists('dateTo', $filterData)) {
            $qb->andWhere('u.lastVisitDate <= :dateTo')
                ->setParameter('dateTo', new DateTime($filterData['dateTo']));
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
