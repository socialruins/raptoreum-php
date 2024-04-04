<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Serializer\Key\HierarchicalKey;

use Raptorio\Blockchain\Base58;
use Raptorio\Blockchain\Key\Deterministic\HierarchicalKey;
use Raptorio\Blockchain\Network\NetworkInterface;

class Base58ExtendedKeySerializer
{
    /**
     * @var ExtendedKeySerializer
     */
    private $serializer;

    /**
     * @param ExtendedKeySerializer $hdSerializer
     */
    public function __construct(ExtendedKeySerializer $hdSerializer)
    {
        $this->serializer = $hdSerializer;
    }

    /**
     * @param NetworkInterface $network
     * @param HierarchicalKey $key
     * @return string
     * @throws \Exception
     */
    public function serialize(NetworkInterface $network, HierarchicalKey $key): string
    {
        return Base58::encodeCheck($this->serializer->serialize($network, $key));
    }

    /**
     * @param NetworkInterface $network
     * @param string $base58
     * @return HierarchicalKey
     * @throws \Raptorio\Blockchain\Exceptions\Base58ChecksumFailure
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public function parse(NetworkInterface $network, string $base58): HierarchicalKey
    {
        return $this->serializer->parse($network, Base58::decodeCheck($base58));
    }
}
