<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\Serializer\Message\GetDataSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\InventorySerializer;
use BitWasp\Buffertools\BufferInterface;

class GetData extends AbstractInventory
{
    /**
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#getdata
     * @return string
     */
    public function getNetworkCommand(): string
    {
        return Message::GETDATA;
    }

    /**
     * {@inheritdoc}
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return (new GetDataSerializer(new InventorySerializer()))->serialize($this);
    }
}
