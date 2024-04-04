<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\Random;

use Raptorio\Blockchain\Exceptions\RandomBytesFailure;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class Random implements RbgInterface
{
    /**
     * Return $length bytes. Throws an exception if
     * @param int $length
     * @return BufferInterface
     * @throws RandomBytesFailure
     */
    public function bytes(int $length = 32): BufferInterface
    {
        return new Buffer(random_bytes($length), $length);
    }
}
