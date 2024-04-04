<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript\Factory;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface;
use Raptorio\Blockchain\Key\KeyToScript\ScriptAndSignData;
use Raptorio\Blockchain\Script\ScriptFactory;
use Raptorio\Blockchain\Script\ScriptType;
use Raptorio\Blockchain\Transaction\Factory\SignData;

class P2wpkhScriptDataFactory extends KeyToScriptDataFactory
{
    /**
     * @return string
     */
    public function getScriptType(): string
    {
        return ScriptType::P2WKH;
    }

    /**
     * @param PublicKeyInterface ...$keys
     * @return ScriptAndSignData
     */
    protected function convertKeyToScriptData(PublicKeyInterface ...$keys): ScriptAndSignData
    {
        if (count($keys) !== 1) {
            throw new \InvalidArgumentException("Invalid number of keys");
        }
        if (!$keys[0]->isCompressed()) {
            throw new \InvalidArgumentException("Cannot create P2WPKH address for non-compressed public key");
        }
        return new ScriptAndSignData(
            ScriptFactory::scriptPubKey()->p2wkh($keys[0]->getPubKeyHash($this->pubKeySerializer)),
            new SignData()
        );
    }
}
