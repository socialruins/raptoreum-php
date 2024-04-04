<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Messages;

use Raptorio\Blockchain\Crypto\EcAdapter\Signature\SignatureInterface;
use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\NetworkSerializable;
use Raptorio\Blockchain\P2P\Serializer\Message\AlertSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\AlertDetailSerializer;
use Raptorio\Blockchain\P2P\Structure\AlertDetail;
use BitWasp\Buffertools\BufferInterface;

class Alert extends NetworkSerializable
{
    /**
     * @var AlertDetail
     */
    private $alert;

    /**
     * @var SignatureInterface
     */
    private $signature;

    /**
     * @param AlertDetail $alert
     * @param SignatureInterface $signature
     */
    public function __construct(AlertDetail $alert, SignatureInterface $signature)
    {
        $this->alert = $alert;
        $this->signature = $signature;
    }

    /**
     * @return string
     * @see https://en.bitcoin.it/wiki/Protocol_documentation#alert
     */
    public function getNetworkCommand(): string
    {
        return Message::ALERT;
    }

    /**
     * @return AlertDetail
     */
    public function getDetail(): AlertDetail
    {
        return $this->alert;
    }

    /**
     * @return SignatureInterface
     */
    public function getSignature(): SignatureInterface
    {
        return $this->signature;
    }

    /**
     * @see \Raptorio\Blockchain\SerializableInterface::getBuffer()
     * @return BufferInterface
     */
    public function getBuffer(): BufferInterface
    {
        return (new AlertSerializer(new AlertDetailSerializer()))->serialize($this);
    }
}
