<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class SendHeaders extends NetworkSerializable
{
    /**
     * {@inheritdoc}
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#sendheaders
     * @see \Raptorio\Blockchain\Network\NetworkSerializableInterface::getNetworkCommand()
     */
    public function getNetworkCommand(): string
    {
        return Message::SENDHEADERS;
    }

    /**
     * {@inheritdoc}
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return new Buffer();
    }
}
