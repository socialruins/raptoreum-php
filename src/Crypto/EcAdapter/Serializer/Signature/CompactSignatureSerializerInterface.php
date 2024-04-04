<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Signature;

use Raptorio\Blockchain\Crypto\EcAdapter\Signature\CompactSignatureInterface;
use BitWasp\Buffertools\BufferInterface;

interface CompactSignatureSerializerInterface
{
    /**
     * @param CompactSignatureInterface $signature
     * @return BufferInterface
     */
    public function serialize(CompactSignatureInterface $signature): BufferInterface;

    /**
     * @param BufferInterface $data
     * @return CompactSignatureInterface
     */
    public function parse(BufferInterface $data): CompactSignatureInterface;
}
