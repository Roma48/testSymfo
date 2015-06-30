<?php

namespace CTO\AppBundle\EventListener;

use CTO\AppBundle\Entity\CtoClient;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;

class DoctrineCTOClientSubscriber implements EventSubscriber
{
    public function onFlush(OnFlushEventArgs $event)
    {
        /** @var EntityManager $em */
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            /** @var CtoClient $entity */
            if ($entity instanceof CtoClient) {
                $entity->setFullName($entity->getFirstName()." ".$entity->getLastName());
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            /** @var CtoClient $entity */
            if ($entity instanceof CtoClient) {
                $entity->setFullName($entity->getFirstName()." ".$entity->getLastName());
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'onFlush'
        ];
    }
}
