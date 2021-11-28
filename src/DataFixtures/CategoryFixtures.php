<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference('User__Admin');

        $cat = (new Category)->setName('Game servers');
        $cat->setOwner($user);
        $this->setReference('Category__GameServers', $cat);
        $manager->persist($cat);

        $cat = (new Category)->setName('LAN servers');
        $cat->setOwner($user);
        $this->setReference('Category__LANServers', $cat);
        $manager->persist($cat);

        $cat = (new Category)->setName('VPS');
        $cat->setOwner($this->getReference('User__User'));
        $this->setReference('Category__VPS', $cat);
        $manager->persist($cat);

        $manager->flush();
    }
}