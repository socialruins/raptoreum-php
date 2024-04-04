<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Block;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Math\Math;
use Raptorio\Blockchain\Script\Opcodes;
use Raptorio\Blockchain\Serializer\Block\BlockHeaderSerializer;
use Raptorio\Blockchain\Serializer\Block\BlockSerializer;
use Raptorio\Blockchain\Serializer\Script\ScriptWitnessSerializer;
use Raptorio\Blockchain\Serializer\Transaction\OutPointSerializer;
use Raptorio\Blockchain\Serializer\Transaction\TransactionInputSerializer;
use Raptorio\Blockchain\Serializer\Transaction\TransactionOutputSerializer;
use Raptorio\Blockchain\Serializer\Transaction\TransactionSerializer;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class BlockFactory
{
    /**
     * @param string $string
     * @param Math|null $math
     * @return BlockInterface
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     * @throws \Exception
     */
    public static function fromHex(string $string, Math $math = null): BlockInterface
    {
        return self::fromBuffer(Buffer::hex($string), $math);
    }

    /**
     * @param BufferInterface $buffer
     * @param Math|null $math
     * @return BlockInterface
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public static function fromBuffer(BufferInterface $buffer, Math $math = null): BlockInterface
    {
        $opcodes = new Opcodes();
        $serializer = new BlockSerializer(
            $math ?: Bitcoin::getMath(),
            new BlockHeaderSerializer(),
            new TransactionSerializer(
                new TransactionInputSerializer(new OutPointSerializer(), $opcodes),
                new TransactionOutputSerializer($opcodes),
                new ScriptWitnessSerializer()
            )
        );

        return $serializer->parse($buffer);
    }
}
