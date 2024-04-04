<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Signature;

use Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface;
use Raptorio\Blockchain\SerializableInterface;

interface TransactionSignatureInterface extends SerializableInterface
{
    /**
     * @return SignatureInterface
     */
    public function getSignature(): SignatureInterface;

    /**
     * @return int
     */
    public function getHashType(): int;

    /**
     * @param TransactionSignatureInterface $other
     * @return bool
     */
    public function equals(TransactionSignatureInterface $other): bool;
}
