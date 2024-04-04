<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\Crypto\Random\Random;
use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use Raptorio\Blockchain\P2P\Serializer\Message\PingSerializer;
use BitWasp\Buffertools\BufferInterface;

class Ping extends NetworkSerializable
{
    /**
     * @var BufferInterface
     */
    private $nonce;

    /**
     * Ping constructor.
     * @param BufferInterface $nonce
     */
    public function __construct(BufferInterface $nonce)
    {
        if ($nonce->getSize() !== 8) {
            throw new \RuntimeException("Invalid nonce size");
        }
        $this->nonce = $nonce;
    }

    /**
     * @param Random $random
     * @return Ping
     * @throws \Raptorio\Blockchain\Exceptions\RandomBytesFailure
     */
    public static function generate(Random $random): Ping
    {
        return new Ping($random->bytes(8));
    }

    /**
     * @return string
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#ping
     */
    public function getNetworkCommand(): string
    {
        return Message::PING;
    }

    /**
     * @return BufferInterface
     */
    public function getNonce(): BufferInterface
    {
        return $this->nonce;
    }

    /**
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new PingSerializer())->serialize($this);
    }
}
