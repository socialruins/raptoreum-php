<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Signature;

use Raptorio\Blockchain\SerializableInterface;

interface SignatureInterface extends SerializableInterface
{
    /**
     * @param SignatureInterface $signature
     * @return bool
     */
    public function equals(SignatureInterface $signature): bool;
}
