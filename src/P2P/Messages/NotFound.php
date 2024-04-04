<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\Serializer\Message\NotFoundSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\InventorySerializer;
use BitWasp\Buffertools\BufferInterface;

class NotFound extends AbstractInventory
{
    /**
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#notfound
     * @return string
     */
    public function getNetworkCommand(): string
    {
        return Message::NOTFOUND;
    }

    /**
     * {@inheritdoc}
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return (new NotFoundSerializer(new InventorySerializer()))->serialize($this);
    }
}
