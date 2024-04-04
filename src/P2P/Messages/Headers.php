<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use Raptorio\Blockchain\P2P\Serializer\Message\HeadersSerializer;
use BitWasp\Buffertools\BufferInterface;

class Headers extends NetworkSerializable implements \Countable
{
    /**
     * @var BufferInterface[]
     */
    private $headers = [];

    public function __construct(BufferInterface ...$headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return string
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#headers
     */
    public function getNetworkCommand(): string
    {
        return Message::HEADERS;
    }

    /**
     * @return BufferInterface[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->headers);
    }

    /**
     * @param int $index
     * @return BufferInterface
     */
    public function getHeader(int $index): BufferInterface
    {
        if (!array_key_exists($index, $this->headers)) {
            throw new \InvalidArgumentException('No header exists at this index');
        }

        return $this->headers[$index];
    }

    /**
     * {@inheritdoc}
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     */
    public function getBuffer(): BufferInterface
    {
        return (new HeadersSerializer())->serialize($this);
    }
}
