<?php
namespace App\Behavior;

interface HasTimestamps
{
    function getCreatedAt(): \DateTimeImmutable;
    function getUpdatedAt(): ?\DateTimeImmutable;
    function update();
}