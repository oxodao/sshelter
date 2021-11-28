<?php

namespace App\Behavior;

use App\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

trait HasOwnerTrait
{
    #[ManyToOne(targetEntity: User::class)]
    #[Groups(['get_owners', 'get_owner', 'post_owner', 'put_owner', 'delete_owner'])]
    private ?User $owner = null;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }
}