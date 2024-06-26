<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Buffertools\BufferInterface;

interface PrivateKeySerializerInterface
{
    /**
     * @param PrivateKeyInterface $privateKey
     * @return BufferInterface
     */
    public function serialize(PrivateKeyInterface $privateKey): BufferInterface;

    /**
     * @param BufferInterface $data
     * @param bool $compressed
     * @return PrivateKeyInterface
     */
    public function parse(BufferInterface $data, bool $compressed): PrivateKeyInterface;
}
