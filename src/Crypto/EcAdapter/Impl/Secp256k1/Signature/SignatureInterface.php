<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Impl\Secp256k1\Signature;

interface SignatureInterface extends \Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface
{
    /**
     * @return resource
     */
    public function getResource();
}
