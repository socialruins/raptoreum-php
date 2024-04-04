<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\Random;

use BitWasp\Buffertools\BufferInterface;

interface RbgInterface
{
    /**
     * Return $numBytes bytes deterministically derived from a seed
     *
     * @param int $numNumBytes
     * @return BufferInterface
     */
    public function bytes(int $numNumBytes): BufferInterface;
}
