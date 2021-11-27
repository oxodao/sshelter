<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistredSubscriber implements EventSubscriber
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
          Events::prePersist,
          Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->processPassword($event);
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->processPassword($event);
    }

    private function processPassword(LifecycleEventArgs $event)
    {
        /** @var User $user */
        $user = $event->getEntity();
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getPlainPassword() || strlen($user->getPlainPassword()) === 0) {
            return;
        }

        $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
    }
}