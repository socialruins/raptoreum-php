<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Structure;

use Raptorio\Blockchain\P2P\Ip\IpInterface;

interface NetworkAddressInterface
{
    /**
     * @return int
     */
    public function getServices(): int;

    /**
     * @return IpInterface
     */
    public function getIp(): IpInterface;

    /**
     * @return int
     */
    public function getPort(): int;
}
