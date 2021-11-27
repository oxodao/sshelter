<?php
namespace App\DataFixtures;

use App\Entity\Machine;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MachineFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $owner */
        $owner = $this->getReference('User__Admin');

        $machine = (new Machine)->setName('My laptop')->setHostname('192.168.1.12')->setPort(22)->setUser($owner);
        $manager->persist($machine);

        $machine = (new Machine)
            ->setName('My server')
            ->setHostname('my.server')
            ->setShortName('srv')
            ->setPort(2222)
            ->setUsername('root')
            ->setForwardedPorts([
                ['local_port' => 5532, 'remote_port' => 5432],
                ['local_port' => 25565, 'remote_port' => 25565],
            ])
            ->setUser($owner);
        $manager->persist($machine);

        /** @var User $owner */
        $owner = $this->getReference('User__User');

        $machine = (new Machine)
            ->setName('Third machine')
            ->setHostname('my.third.machine')
            ->setPort(12345)
            ->setUsername('user')
            ->setForwardedPorts([
                ['local_port' => 3366, 'remote_port' => 3366]
            ])
            ->setUser($owner);
        $manager->persist($machine);

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}