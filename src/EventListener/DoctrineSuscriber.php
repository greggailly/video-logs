<?php

namespace App\EventListener;

use App\Entity\Log;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DoctrineSuscriber implements EventSubscriber
{
    private $logger;

    public function __construct(LoggerInterface $dbLogger)
    {
        $this->logger = $dbLogger;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->log('ajouté', $args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->log('modifé', $args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->log('supprimé', $args);
    }

    public function log($message, $args)
    {
        $entity = $args->getEntity();
        if (!($entity instanceof Log)) {
            $this->logger->info($entity->getId() . $message);
        }
    }
}
