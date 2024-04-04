<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Signature;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Buffertools\BufferInterface;

class SignatureSort implements SignatureSortInterface
{
    /**
     * @var EcAdapterInterface
     */
    private $ecAdapter;

    /**
     * SignatureSort constructor.
     * @param EcAdapterInterface $ecAdapter
     */
    public function __construct(EcAdapterInterface $ecAdapter = null)
    {
        $this->ecAdapter = $ecAdapter ?: Bitcoin::getEcAdapter();
    }

    /**
     * @param \Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface[] $signatures
     * @param \Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface[] $publicKeys
     * @param BufferInterface $messageHash
     * @return \SplObjectStorage
     */
    public function link(array $signatures, array $publicKeys, BufferInterface $messageHash): \SplObjectStorage
    {
        $sigCount = count($signatures);
        $storage = new \SplObjectStorage();
        foreach ($signatures as $signature) {
            foreach ($publicKeys as $key) {
                if ($key->verify($messageHash, $signature)) {
                    $storage->attach($key, $signature);
                    if (count($storage) === $sigCount) {
                        break 2;
                    }

                    break;
                }
            }
        }

        return $storage;
    }
}
