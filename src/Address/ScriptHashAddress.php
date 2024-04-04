<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Address;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Network\NetworkInterface;
use Raptorio\Blockchain\Script\ScriptFactory;
use Raptorio\Blockchain\Script\ScriptInterface;
use BitWasp\Buffertools\BufferInterface;

class ScriptHashAddress extends Base58Address
{
    /**
     * ScriptHashAddress constructor.
     * @param BufferInterface $data
     */
    public function __construct(BufferInterface $data)
    {
        if ($data->getSize() !== 20) {
            throw new \RuntimeException("P2SH address hash should be 20 bytes");
        }

        parent::__construct($data);
    }

    /**
     * @param NetworkInterface $network
     * @return string
     */
    public function getPrefixByte(NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();
        return pack("H*", $network->getP2shByte());
    }

    /**
     * @return ScriptInterface
     */
    public function getScriptPubKey(): ScriptInterface
    {
        return ScriptFactory::scriptPubKey()->payToScriptHash($this->getHash());
    }
}
