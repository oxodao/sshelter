<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Behavior\HasOwner;
use App\Behavior\HasOwnerTrait;
use App\Behavior\HasTimestamps;
use App\Behavior\HasTimestampsTrait;
use App\Filters\SelfFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Entity]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => ['get_categories', 'get_timestamps', 'get_machine']]],
        'post' => [
            'normalization_context' => ['groups' => ['get_category', 'get_timestamps']],
            'denormalization_context' => ['groups' => ['post_category', 'get_machine']],
        ],
    ],
    itemOperations: [
        'get'    => ['normalization_context' => ['groups' => ['get_category', 'get_timestamps', 'get_machine']]],
        'put'    => [
            'normalization_context' => ['groups' => ['get_category', 'get_timestamps']],
            'denormalization_context' => ['groups' => ['put_category', 'get_machine']]
        ],
        'delete'
    ],
    attributes: ['pagination_enabled' => false]
)]
#[ApiFilter(SelfFilter::class)]
class Category implements HasOwner, HasTimestamps
{
    use HasTimestampsTrait;
    use HasOwnerTrait;

    #[Id]
    #[GeneratedValue(strategy: 'NONE')]
    #[Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[Column(type: 'string', nullable: false)]
    #[NotBlank]
    #[Groups(['get_categories', 'get_category', 'post_category', 'put_category', 'delete_category'])]
    private ?string $name = null;

    #[OneToMany(mappedBy: 'category', targetEntity: Machine::class)]
    #[Groups(['get_categories', 'get_category', 'post_category', 'put_category', 'delete_category'])]
    private Collection $machines;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->machines = new ArrayCollection;

        $this->initializeTimestamps();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if ($this->machines->contains($machine)) {
            $this->machines->add($machine);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->contains($machine)) {
            $this->machines->removeElement($machine);
        }

        return $this;
    }
}