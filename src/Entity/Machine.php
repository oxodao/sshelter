<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Behavior\HasOwner;
use App\Behavior\HasOwnerTrait;
use App\Behavior\HasTimestamps;
use App\Behavior\HasTimestampsTrait;
use App\Filters\SelfFilter;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[Entity]
#[UniqueEntity(['name', 'user'])]
#[UniqueEntity(['shortName', 'user'])]
#[ApiResource(
    collectionOperations: [
        'get'  => [
            'normalization_context' => ['groups' => ['get_machines', 'get_timestamps']],
        ],
        'post' => [
            'denormalization_context' => ['groups' => ['post_machine']],
            'normalization_context'   => ['groups' => ['get_machine', 'get_timestamps']],
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['get_machine', 'get_timestamps']],
        ],
        'put' => [
            'denormalization_context' => ['groups' => ['put_machine']],
            'normalization_context'   => ['groups' => ['get_machine', 'get_timestamps']],
        ],
        'delete'
    ],
    attributes: ['pagination_enabled' => false]
)]
#[ApiFilter(SelfFilter::class)]
class Machine implements HasOwner, HasTimestamps
{
    use HasTimestampsTrait;
    use HasOwnerTrait;

    #[Id]
    #[GeneratedValue(strategy: 'NONE')]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[Column(type: 'string', length: 40)]
    #[NotBlank]
    #[Length(min: 3, max: 40)]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private ?string $name = null;

    #[Column(type: 'string', length: 40, nullable: true)]
    #[Length(max: 40)]
    #[Regex(pattern: '/^([a-z0-9]|-|\/|_|\.|\|&|\$)+$/i', message: 'Your name must contain only letter, numbers and -/_.|&$')]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private ?string $shortName = null;

    #[Column(type: 'string', length: 255)]
    #[NotBlank]
    #[Length(min: 1, max: 255)]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private ?string $hostname = null;

    #[Column(type: 'integer')]
    #[NotBlank]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private ?int $port = null;

    #[Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private ?string $username = null;

    #[Column(type: 'text', nullable: true)]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private ?string $otherSettings = null;

    // @TODO: Find a way to validate them
    #[Column(type: 'json')]
    #[Groups(['get_machine', 'get_machines', 'post_machine', 'put_machine'])]
    private array $forwardedPorts = [];

    #[ManyToOne(targetEntity: Category::class, inversedBy: 'machines')]
    private ?Category $category = null;

    public function __construct()
    {
        $this->id = Uuid::v4();

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

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(?string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getOtherSettings(): ?string
    {
        return $this->otherSettings;
    }

    public function setOtherSettings(?string $os): self
    {
        $this->otherSettings = $os;

        return $this;
    }

    public function getForwardedPorts(): array
    {
        return $this->forwardedPorts;
    }

    public function setForwardedPorts(array $forwardedPorts): self
    {
        $this->forwardedPorts = $forwardedPorts;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
