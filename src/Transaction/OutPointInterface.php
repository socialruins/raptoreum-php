<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Transaction;

use Raptorio\Blockchain\SerializableInterface;
use BitWasp\Buffertools\BufferInterface;

interface OutPointInterface extends SerializableInterface
{
    /**
     * @return BufferInterface
     */
    public function getTxId(): BufferInterface;

    /**
     * @return int
     */
    public function getVout(): int;

    /**
     * @param OutPointInterface $outPoint
     * @return bool
     */
    public function equals(OutPointInterface $outPoint): bool;
}
