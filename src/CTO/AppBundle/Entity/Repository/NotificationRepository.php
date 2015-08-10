<?php

namespace CTO\AppBundle\Entity\Repository;

use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\Notification;
use DateTime;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getNotificationCountForLast3day(CtoUser $user, DateTime $from, DateTime $to)
    {
        return $this->createQueryBuilder('j')
            ->select('count(j) as NotifCount')
            ->join('j.clientCto', 'cl')
            ->andWhere('cl.cto = :cto')->setParameter('cto', $user)
            ->andWhere('j.whenSend >= :from')->setParameter('from', $from)
            ->andWhere('j.whenSend <= :to')->setParameter('to', $to)
            ->andWhere('j.status = :inprogress')->setParameter('inprogress', Notification::STATUS_SEND_IN_PROGRESS)
            ->getQuery()
            ->getSingleResult();
    }

    public function getCurrents(CtoUser $user, $from, $to)
    {
        return $this->createQueryBuilder('j')
            ->join('j.clientCto', 'cl')
            ->andWhere('cl.cto = :cto')->setParameter('cto', $user)
            ->andWhere('j.whenSend >= :from')->setParameter('from', $from)
            ->andWhere('j.whenSend <= :to')->setParameter('to', $to)
            ->andWhere('j.status = :inprogress')->setParameter('inprogress', Notification::STATUS_SEND_IN_PROGRESS)
            ->orderBy('j.whenSend', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getPlanned($user, $from)
    {
        return $this->createQueryBuilder('j')
            ->join('j.clientCto', 'cl')
            ->andWhere('cl.cto = :cto')->setParameter('cto', $user)
            ->andWhere('j.whenSend >= :from')->setParameter('from', $from)
            ->andWhere('j.status = :inprogress')->setParameter('inprogress', Notification::STATUS_SEND_IN_PROGRESS)
            ->orderBy('j.whenSend', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getSentOut($user)
    {
        return $this->createQueryBuilder('j')
            ->join('j.clientCto', 'cl')
            ->andWhere('cl.cto = :cto')->setParameter('cto', $user)
            ->andWhere('j.status <> :inprogress')->setParameter('inprogress', Notification::STATUS_SEND_IN_PROGRESS)
            ->orderBy('j.whenSend', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
