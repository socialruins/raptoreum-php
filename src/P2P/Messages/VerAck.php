<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class VerAck extends NetworkSerializable
{
    /**
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#verack
     * @return string
     */
    public function getNetworkCommand(): string
    {
        return Message::VERACK;
    }

    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return new Buffer();
    }
}
