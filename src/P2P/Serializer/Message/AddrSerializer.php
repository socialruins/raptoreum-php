<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Serializer\Message;

use Raptorio\Blockchain\P2P\Messages\Addr;
use Raptorio\Blockchain\P2P\Serializer\Structure\NetworkAddressTimestampSerializer;
use Raptorio\Blockchain\Serializer\Types;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

class AddrSerializer
{
    /**
     * @var \BitWasp\Buffertools\Types\Vector
     */
    private $vectorNetAddr;

    /**
     * @param NetworkAddressTimestampSerializer $serializer
     */
    public function __construct(NetworkAddressTimestampSerializer $serializer)
    {
        $this->vectorNetAddr = Types::vector([$serializer, 'fromParser']);
    }

    /**
     * @param Parser $parser
     * @return Addr
     */
    public function fromParser(Parser $parser): Addr
    {
        $addresses = $this->vectorNetAddr->read($parser);
        return new Addr($addresses);
    }

    /**
     * @param BufferInterface $data
     * @return Addr
     */
    public function parse(BufferInterface $data): Addr
    {
        return $this->fromParser(new Parser($data));
    }

    /**
     * @param Addr $addr
     * @return BufferInterface
     */
    public function serialize(Addr $addr): BufferInterface
    {
        return new Buffer($this->vectorNetAddr->write($addr->getAddresses()));
    }
}
