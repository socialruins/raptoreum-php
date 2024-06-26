<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Transaction;

use Raptorio\Blockchain\Serializer\Transaction\TransactionSerializer;
use Raptorio\Blockchain\Transaction\Factory\TxBuilder;
use Raptorio\Blockchain\Transaction\Mutator\TxMutator;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class TransactionFactory
{
    /**
     * @return TxBuilder
     */
    public static function build(): TxBuilder
    {
        return new TxBuilder();
    }

    /**
     * @param TransactionInterface $transaction
     * @return TxMutator
     */
    public static function mutate(TransactionInterface $transaction): TxMutator
    {
        return new TxMutator($transaction);
    }

    /**
     * @param string $hex
     * @return TransactionInterface
     * @throws \Exception
     */
    public static function fromHex(string $hex): TransactionInterface
    {
        return self::fromBuffer(Buffer::hex($hex));
    }

    /**
     * @param BufferInterface $buffer
     * @return TransactionInterface
     */
    public static function fromBuffer(BufferInterface $buffer): TransactionInterface
    {
        return (new TransactionSerializer())->parse($buffer);
    }
}
