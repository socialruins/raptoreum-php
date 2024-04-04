<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Serializer\Transaction;

use Raptorio\Blockchain\Transaction\OutPointInterface;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

interface OutPointSerializerInterface
{
    /**
     * @param OutPointInterface $outpoint
     * @return BufferInterface
     */
    public function serialize(OutPointInterface $outpoint): BufferInterface;

    /**
     * @param Parser $parser
     * @return OutPointInterface
     */
    public function fromParser(Parser $parser): OutPointInterface;

    /**
     * @param BufferInterface $data
     * @return OutPointInterface
     */
    public function parse(BufferInterface $data): OutPointInterface;
}
