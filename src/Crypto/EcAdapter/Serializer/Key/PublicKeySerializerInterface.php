<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Buffertools\BufferInterface;

interface PublicKeySerializerInterface
{
    /**
     * @param PublicKeyInterface $publicKey
     * @return BufferInterface
     */
    public function serialize(PublicKeyInterface $publicKey): BufferInterface;

    /**
     * @param BufferInterface $data
     * @return PublicKeyInterface
     */
    public function parse(BufferInterface $data): PublicKeyInterface;
}
