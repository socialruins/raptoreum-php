<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Network\NetworkInterface;
use Raptorio\Blockchain\Serializable;

abstract class NetworkSerializable extends Serializable implements NetworkSerializableInterface
{
    /**
     * @param NetworkInterface $network
     * @return NetworkMessage
     */
    public function getNetworkMessage(NetworkInterface $network = null): NetworkMessage
    {
        return new NetworkMessage(
            $network ?: Bitcoin::getNetwork(),
            $this
        );
    }
}
