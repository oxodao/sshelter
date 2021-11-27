<?php
namespace App\ValueObject;

use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class ForwardedPort
{
    public function __construct(
        // This is the hostname on the user's machine
        private string $localHost,
        // This is the port on the user's machine
        private string $localPort,
        // This is the host on the server
        private string $remoteHost,
        // This is the port on the server
        private string $remotePort,
    )
    {
    }

    public function getLocalHost(): string
    {
        return $this->localHost;
    }

    public function setLocalHost(string $localHost): self
    {
        $this->localHost = $localHost;

        return $this;
    }

    public function getLocalPort(): string
    {
        return $this->localPort;
    }

    public function setLocalPort(string $localPort): self
    {
        $this->localPort = $localPort;

        return $this;
    }

    public function getRemoteHost(): string
    {
        return $this->remoteHost;
    }

    public function setRemoteHost(string $remoteHost): self
    {
        $this->remoteHost = $remoteHost;

        return $this;
    }

    public function getRemotePort(): string
    {
        return $this->remotePort;
    }

    public function setRemotePort(string $remotePort): self
    {
        $this->remotePort = $remotePort;

        return $this;
    }
}
