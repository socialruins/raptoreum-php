<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Serializer\Message;

use Raptorio\Blockchain\P2P\Messages\Pong;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

class PongSerializer
{
    /**
     * @param Pong $pong
     * @return BufferInterface
     */
    public function serialize(Pong $pong): BufferInterface
    {
        return $pong->getNonce();
    }

    /**
     * @param Parser $parser
     * @return Pong
     */
    public function fromParser(Parser $parser): Pong
    {
        return new Pong($parser->readBytes(8));
    }

    /**
     * @param BufferInterface $data
     * @return Pong
     */
    public function parse(BufferInterface $data): Pong
    {
        return $this->fromParser(new Parser($data));
    }
}
