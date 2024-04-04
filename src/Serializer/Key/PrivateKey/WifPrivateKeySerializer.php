<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Serializer\Key\PrivateKey;

use Raptorio\Blockchain\Base58;
use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Key\PrivateKeyInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key\PrivateKeySerializerInterface;
use Raptorio\Blockchain\Exceptions\Base58ChecksumFailure;
use Raptorio\Blockchain\Exceptions\InvalidPrivateKey;
use Raptorio\Blockchain\Network\NetworkInterface;
use BitWasp\Buffertools\Buffer;

class WifPrivateKeySerializer
{
    /**
     * @var PrivateKeySerializerInterface
     */
    private $keySerializer;

    /**
     * @param PrivateKeySerializerInterface $serializer
     */
    public function __construct(PrivateKeySerializerInterface $serializer)
    {
        $this->keySerializer = $serializer;
    }

    /**
     * @param NetworkInterface $network
     * @param PrivateKeyInterface $privateKey
     * @return string
     * @throws \Exception
     */
    public function serialize(NetworkInterface $network, PrivateKeyInterface $privateKey): string
    {
        $prefix = pack("H*", $network->getPrivByte());
        if ($privateKey->isCompressed()) {
            $ending = "\x01";
        } else {
            $ending = "";
        }

        return Base58::encodeCheck(new Buffer("{$prefix}{$this->keySerializer->serialize($privateKey)->getBinary()}{$ending}"));
    }

    /**
     * @param string $wif
     * @param NetworkInterface|null $network
     * @return PrivateKeyInterface
     * @throws Base58ChecksumFailure
     * @throws InvalidPrivateKey
     * @throws \Exception
     */
    public function parse(string $wif, NetworkInterface $network = null): PrivateKeyInterface
    {
        $network = $network ?: Bitcoin::getNetwork();
        $data = Base58::decodeCheck($wif);
        if ($data->slice(0, 1)->getHex() !== $network->getPrivByte()) {
            throw new \RuntimeException('WIF prefix does not match networks');
        }

        $payload = $data->slice(1);
        $size = $payload->getSize();

        if (33 === $size) {
            $compressed = true;
            $payload = $payload->slice(0, 32);
        } else if (32 === $size) {
            $compressed = false;
        } else {
            throw new InvalidPrivateKey("Private key should be always be 32 or 33 bytes (depending on if it's compressed)");
        }

        return $this->keySerializer->parse($payload, $compressed);
    }
}
