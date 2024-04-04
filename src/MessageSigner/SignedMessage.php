<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\MessageSigner;

use Raptorio\Blockchain\Crypto\EcAdapter\EcSerializer;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Signature\CompactSignatureSerializerInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Signature\CompactSignatureInterface;
use Raptorio\Blockchain\Serializer\MessageSigner\SignedMessageSerializer;

class SignedMessage
{

    /**
     * @var string
     */
    private $message;

    /**
     * @var CompactSignatureInterface
     */
    private $compactSignature;

    /**
     * @param string $message
     * @param CompactSignatureInterface $signature
     */
    public function __construct(string $message, CompactSignatureInterface $signature)
    {
        $this->message = $message;
        $this->compactSignature = $signature;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return CompactSignatureInterface
     */
    public function getCompactSignature(): CompactSignatureInterface
    {
        return $this->compactSignature;
    }

    /**
     * @return \BitWasp\Buffertools\BufferInterface
     */
    public function getBuffer()
    {
        $serializer = new SignedMessageSerializer(
            EcSerializer::getSerializer(CompactSignatureSerializerInterface::class)
        );
        return $serializer->serialize($this);
    }
}
