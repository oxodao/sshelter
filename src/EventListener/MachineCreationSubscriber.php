<?php

namespace App\EventListener;

use App\Behavior\HasOwner;
use App\Entity\Machine;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

class MachineCreationSubscriber implements EventSubscriber
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        /** @var Machine $machine */
        $machine = $event->getEntity();

        if (!$machine instanceof HasOwner) {
            return;
        }

        if ($this->security->getUser())
        {
            $machine->setOwner($this->security->getUser());
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }
}