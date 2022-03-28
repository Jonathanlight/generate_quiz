<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

final class TimestampableListener
{
    /**
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (true === property_exists($entity, 'createdAt')) {
            $entity->setCreatedAt(new \DateTime());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (true === property_exists($entity, 'updatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
