<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Transaction\Factory\Checker;

use Raptorio\Blockchain\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Raptorio\Blockchain\Script\Interpreter\CheckerBase;
use Raptorio\Blockchain\Serializer\Signature\TransactionSignatureSerializer;
use Raptorio\Blockchain\Transaction\TransactionInterface;
use Raptorio\Blockchain\Transaction\TransactionOutputInterface;

abstract class CheckerCreatorBase
{
    /**
     * @var EcAdapterInterface
     */
    protected $ecAdapter;

    /**
     * @var TransactionSignatureSerializer
     */
    protected $txSigSerializer;

    /**
     * @var PublicKeySerializerInterface
     */
    protected $pubKeySerializer;

    /**
     * CheckerCreator constructor.
     * @param EcAdapterInterface $ecAdapter
     * @param TransactionSignatureSerializer $txSigSerializer
     * @param PublicKeySerializerInterface $pubKeySerializer
     */
    public function __construct(
        EcAdapterInterface $ecAdapter,
        TransactionSignatureSerializer $txSigSerializer,
        PublicKeySerializerInterface $pubKeySerializer
    ) {
        $this->ecAdapter = $ecAdapter;
        $this->txSigSerializer = $txSigSerializer;
        $this->pubKeySerializer = $pubKeySerializer;
    }

    /**
     * @param TransactionInterface $tx
     * @param int $nInput
     * @param TransactionOutputInterface $txOut
     * @return CheckerBase
     */
    abstract public function create(TransactionInterface $tx, int $nInput, TransactionOutputInterface $txOut): CheckerBase;
}
