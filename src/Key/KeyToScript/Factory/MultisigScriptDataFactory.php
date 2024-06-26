<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript\Factory;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Raptorio\Blockchain\Key\KeyToScript\ScriptAndSignData;
use Raptorio\Blockchain\Script\ScriptFactory;
use Raptorio\Blockchain\Script\ScriptType;
use Raptorio\Blockchain\Transaction\Factory\SignData;

class MultisigScriptDataFactory extends KeyToScriptDataFactory
{
    /**
     * @var int
     */
    private $numSigners;

    /**
     * @var int
     */
    private $numKeys;

    /**
     * @var bool
     */
    private $sortKeys;

    public function __construct(int $numSigners, int $numKeys, bool $sortKeys, PublicKeySerializerInterface $pubKeySerializer = null)
    {
        $this->numSigners = $numSigners;
        $this->numKeys = $numKeys;
        $this->sortKeys = $sortKeys;
        parent::__construct($pubKeySerializer);
    }

    /**
     * @return string
     */
    public function getScriptType(): string
    {
        return ScriptType::MULTISIG;
    }

    /**
     * @param PublicKeyInterface ...$keys
     * @return ScriptAndSignData
     */
    protected function convertKeyToScriptData(PublicKeyInterface ...$keys): ScriptAndSignData
    {
        if (count($keys) !== $this->numKeys) {
            throw new \InvalidArgumentException("Incorrect number of keys");
        }

        $keyBuffers = [];
        for ($i = 0; $i < $this->numKeys; $i++) {
            $keyBuffers[] = $this->pubKeySerializer->serialize($keys[$i]);
        }

        return new ScriptAndSignData(
            ScriptFactory::scriptPubKey()->multisigKeyBuffers($this->numSigners, $keyBuffers, $this->sortKeys),
            new SignData()
        );
    }
}
