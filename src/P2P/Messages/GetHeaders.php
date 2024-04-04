<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\Serializer\Message\GetHeadersSerializer;
use Raptorio\Blockchain\Serializer\Chain\BlockLocatorSerializer;
use BitWasp\Buffertools\BufferInterface;

class GetHeaders extends AbstractBlockLocator
{
    /**
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#getheaders
     * @return string
     */
    public function getNetworkCommand(): string
    {
        return Message::GETHEADERS;
    }

    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new GetHeadersSerializer(new BlockLocatorSerializer()))->serialize($this);
    }
}
