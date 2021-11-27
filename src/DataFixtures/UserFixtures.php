<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = (new User)->setUsername('admin')->setPlainPassword('admin');
        $this->setReference('User__Admin', $user);
        $manager->persist($user);

        $user = (new User)->setUsername('user')->setPlainPassword('user');
        $this->setReference('User__User', $user);
        $manager->persist($user);

        $manager->flush();
    }
}