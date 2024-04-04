<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Crypto\EcAdapter\Key;

use Raptorio\Blockchain\Crypto\EcAdapter\Impl\PhpEcc\Signature\CompactSignature;
use Raptorio\Blockchain\Crypto\EcAdapter\Signature\CompactSignatureInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface;
use Raptorio\Blockchain\Crypto\Random\RbgInterface;
use Raptorio\Blockchain\Network\NetworkInterface;
use BitWasp\Buffertools\BufferInterface;

interface PrivateKeyInterface extends KeyInterface
{
    /**
     * Return the decimal secret multiplier
     *
     * @return \GMP
     */
    public function getSecret();

    /**
     * @param BufferInterface $msg32
     * @param RbgInterface $rbg
     * @return SignatureInterface
     */
    public function sign(BufferInterface $msg32, RbgInterface $rbg = null);

    /**
     * @param BufferInterface $msg32
     * @param RbgInterface|null $rbgInterface
     * @return CompactSignature
     */
    public function signCompact(BufferInterface $msg32, RbgInterface $rbgInterface = null);

    /**
     * Return the public key.
     *
     * @return PublicKeyInterface
     */
    public function getPublicKey();

    /**
     * Convert the private key to wallet import format. This function
     * optionally takes a NetworkInterface for exporting keys for other networks.
     *
     * @param NetworkInterface $network
     * @return string
     */
    public function toWif(NetworkInterface $network = null);
}
