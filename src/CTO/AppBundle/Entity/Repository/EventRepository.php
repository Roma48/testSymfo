<?php

namespace CTO\AppBundle\Entity\Repository;

use CTO\AppBundle\Entity\CtoUser;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    /**
     * @param $date
     * @param $cto
     * @return array
     */
    public function findByDate($date, CtoUser $cto)
    {
        $dayStart = new \DateTime($date . ' 00:00:00');
        $dayEnd = new \DateTime($date . ' 23:59:59');

        $query = $this->createQueryBuilder('event')
            ->select('event')
            ->where(':startDay <= event.startAt AND event.startAt <= :endDay')
            ->orWhere(':startDay <= event.endAt AND event.endAt <= :endDay')
            ->orWhere(':startDay >= event.startAt AND event.endAt >= :endDay')
            ->andWhere(':cto = event.cto')
            ->setParameter('startDay', $dayStart)
            ->setParameter('endDay', $dayEnd)
            ->setParameter('cto', $cto)
            ->getQuery()
            ->getResult();

        return $query;
    }

}