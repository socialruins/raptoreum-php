<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class MemPool extends NetworkSerializable
{
    /**
     * @see \Raptorio\Blockchain\Network\NetworkSerializableInterface::getNetworkCommand()
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#mempool
     */
    public function getNetworkCommand(): string
    {
        return Message::MEMPOOL;
    }

    /**
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return new Buffer();
    }
}
