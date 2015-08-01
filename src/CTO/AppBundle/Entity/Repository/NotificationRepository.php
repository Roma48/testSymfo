<?php

namespace CTO\AppBundle\Entity\Repository;

use CTO\AppBundle\Entity\CtoUser;
use DateTime;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getNotificationCountForLast3day(CtoUser $user, DateTime $from, DateTime $to)
    {
        return $this->createQueryBuilder('j')
            ->select('count(j) as NotifCount')
            ->join('j.clientCto', 'cl')
            ->andWhere('cl.cto = :cto')
            ->setParameter('cto', $user)
            ->andWhere('j.whenSend >= :from')
            ->setParameter('from', $from)
            ->andWhere('j.whenSend <= :to')
            ->setParameter('to', $to)
            ->getQuery()
            ->getSingleResult();
    }
}
