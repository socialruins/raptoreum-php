<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Serializer\Message;

use Raptorio\Blockchain\P2P\Messages\Inv;
use Raptorio\Blockchain\P2P\Serializer\Structure\InventorySerializer;
use Raptorio\Blockchain\Serializer\Types;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Types\Vector;

class InvSerializer
{
    /**
     * @var Vector
     */
    private $vectorInventory;

    /**
     * @param InventorySerializer $invVector
     */
    public function __construct(InventorySerializer $invVector)
    {
        $this->vectorInventory = Types::vector([$invVector, 'fromParser']);
    }

    /**
     * @param Parser $parser
     * @return Inv
     */
    public function fromParser(Parser $parser): Inv
    {
        $items = $this->vectorInventory->read($parser);
        return new Inv($items);
    }

    /**
     * @param BufferInterface $data
     * @return Inv
     */
    public function parse(BufferInterface $data): Inv
    {
        return $this->fromParser(new Parser($data));
    }

    /**
     * @param Inv $inv
     * @return BufferInterface
     */
    public function serialize(Inv $inv): BufferInterface
    {
        return new Buffer($this->vectorInventory->write($inv->getItems()));
    }
}
