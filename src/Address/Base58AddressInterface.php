<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Address;

use Raptorio\Blockchain\Network\NetworkInterface;

interface Base58AddressInterface extends AddressInterface
{
    /**
     * @param NetworkInterface $network
     * @return string
     */
    public function getPrefixByte(NetworkInterface $network = null): string;
}
