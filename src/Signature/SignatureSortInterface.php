<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Signature;

use BitWasp\Buffertools\BufferInterface;

interface SignatureSortInterface
{
    /**
     * @param \Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface[] $signatures
     * @param \Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface[] $publicKeys
     * @param BufferInterface $messageHash
     * @return \SplObjectStorage
     */
    public function link(array $signatures, array $publicKeys, BufferInterface $messageHash): \SplObjectStorage;
}
