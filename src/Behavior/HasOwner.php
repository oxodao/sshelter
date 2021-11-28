<?php

namespace App\Behavior;

use App\Entity\User;

interface HasOwner
{
    function getOwner(): ?User;
    function setOwner(?User $user);
}