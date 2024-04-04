<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P;

use Raptorio\Blockchain\SerializableInterface;

interface NetworkSerializableInterface extends SerializableInterface
{
    /**
     * @return string
     */
    public function getNetworkCommand(): string;

    /**
     * @return NetworkMessage
     */
    public function getNetworkMessage(): NetworkMessage;
}
