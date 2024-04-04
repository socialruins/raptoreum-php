<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Structure;

use Raptorio\Blockchain\P2P\Ip\IpInterface;
use Raptorio\Blockchain\P2P\Serializer\Structure\NetworkAddressSerializer;
use Raptorio\Blockchain\Serializable;
use BitWasp\Buffertools\BufferInterface;

class NetworkAddress extends Serializable implements NetworkAddressInterface
{
    /**
     * @var int
     */
    private $services;

    /**
     * @var IpInterface
     */
    private $ip;

    /**
     * @var int
     */
    private $port;

    /**
     * @param int $services
     * @param IpInterface $ip
     * @param int $port
     */
    public function __construct(int $services, IpInterface $ip, int $port)
    {
        $this->services = $services;
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getServices(): int
    {
        return $this->services;
    }

    /**
     * @return IpInterface
     */
    public function getIp(): IpInterface
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new NetworkAddressSerializer())->serialize($this);
    }
}
