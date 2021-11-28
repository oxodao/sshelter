<?php
namespace App\EventListener;

use App\Behavior\HasTimestamps;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TimestampsSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        /** @var HasTimestamps $ts */
        $ts = $event->getEntity();

        if (!$ts instanceof HasTimestamps) {
            return;
        }

        $ts->update();
    }
}