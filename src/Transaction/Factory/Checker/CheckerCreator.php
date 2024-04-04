<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Transaction\Factory\Checker;

use Raptorio\Blockchain\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\EcSerializer;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Signature\DerSignatureSerializerInterface;
use Raptorio\Blockchain\Script\Interpreter\CheckerBase;
use Raptorio\Blockchain\Script\Interpreter\Checker;
use Raptorio\Blockchain\Serializer\Signature\TransactionSignatureSerializer;
use Raptorio\Blockchain\Transaction\TransactionInterface;
use Raptorio\Blockchain\Transaction\TransactionOutputInterface;

class CheckerCreator extends CheckerCreatorBase
{
    public static function fromEcAdapter(EcAdapterInterface $ecAdapter)
    {
        $derSigSer = EcSerializer::getSerializer(DerSignatureSerializerInterface::class);
        $txSigSer = new TransactionSignatureSerializer($derSigSer);
        $pkSer = EcSerializer::getSerializer(PublicKeySerializerInterface::class);
        return new CheckerCreator($ecAdapter, $txSigSer, $pkSer);
    }
    /**
     * @param TransactionInterface $tx
     * @param int $nInput
     * @param TransactionOutputInterface $txOut
     * @return CheckerBase
     */
    public function create(TransactionInterface $tx, int $nInput, TransactionOutputInterface $txOut): CheckerBase
    {
        return new Checker($this->ecAdapter, $tx, $nInput, $txOut->getValue(), $this->txSigSerializer, $this->pubKeySerializer);
    }
}
