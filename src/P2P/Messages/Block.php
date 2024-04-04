<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use BitWasp\Buffertools\BufferInterface;

class Block extends NetworkSerializable
{
    /**
     * @var BufferInterface
     */
    private $blockData;

    public function __construct(BufferInterface $blockData)
    {
        $this->blockData = $blockData;
    }

    /**
     * {@inheritdoc}
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#block
     * @see \Raptorio\Blockchain\Network\NetworkSerializableInterface::getNetworkCommand()
     */
    public function getNetworkCommand(): string
    {
        return Message::BLOCK;
    }

    /**
     * @return BufferInterface
     */
    public function getBlock(): BufferInterface
    {
        return $this->blockData;
    }

    /**
     * {@inheritdoc}
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return $this->blockData;
    }
}
