<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Signature;

use Raptorio\Blockchain\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Buffertools\BufferInterface;

interface DerSignatureSerializerInterface
{
    /**
     * @return EcAdapterInterface
     */
    public function getEcAdapter();

    /**
     * @param SignatureInterface $signature
     * @return BufferInterface
     */
    public function serialize(SignatureInterface $signature): BufferInterface;

    /**
     * @param BufferInterface $derSignature
     * @return SignatureInterface
     */
    public function parse(BufferInterface $derSignature): SignatureInterface;
}
