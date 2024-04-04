<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Serializer\Message;

use Raptorio\Blockchain\P2P\Messages\GetData;
use Raptorio\Blockchain\P2P\Serializer\Structure\InventorySerializer;
use Raptorio\Blockchain\Serializer\Types;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

class GetDataSerializer
{
    /**
     * @var \BitWasp\Buffertools\Types\Vector
     */
    private $vectorInt;

    /**
     * @param InventorySerializer $inv
     */
    public function __construct(InventorySerializer $inv)
    {
        $this->vectorInt = Types::vector([$inv, 'fromParser']);
    }

    /**
     * @param Parser $parser
     * @return GetData
     */
    public function fromParser(Parser $parser): GetData
    {
        $addrs = $this->vectorInt->read($parser);
        return new GetData($addrs);
    }

    /**
     * @param BufferInterface $data
     * @return GetData
     */
    public function parse(BufferInterface $data): GetData
    {
        return $this->fromParser(new Parser($data));
    }

    /**
     * @param GetData $getData
     * @return BufferInterface
     */
    public function serialize(GetData $getData): BufferInterface
    {
        return new Buffer($this->vectorInt->write($getData->getItems()));
    }
}
