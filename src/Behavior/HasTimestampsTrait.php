<?php

namespace App\Behavior;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Serializer\Annotation\Groups;

trait HasTimestampsTrait
{
    #[Column(type: 'datetime_immutable', nullable: false)]
    #[Groups(['get_timestamps'])]
    private ?DateTimeImmutable $createdAt = null;

    #[Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['get_timestamps'])]
    private ?DateTimeImmutable $updatedAt = null;

    private function initializeTimestamps()
    {
        $this->createdAt = new DateTimeImmutable;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function update()
    {
        $this->updatedAt = new DateTimeImmutable;
    }
}