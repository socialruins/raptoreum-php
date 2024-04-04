<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript\Factory;

use Raptorio\Blockchain\Crypto\EcAdapter\EcSerializer;
use Raptorio\Blockchain\Crypto\EcAdapter\Key\KeyInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Key\PrivateKeyInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Raptorio\Blockchain\Key\KeyToScript\ScriptAndSignData;
use Raptorio\Blockchain\Key\KeyToScript\ScriptDataFactory;

abstract class KeyToScriptDataFactory extends ScriptDataFactory
{
    /**
     * @var PublicKeySerializerInterface
     */
    protected $pubKeySerializer;

    /**
     * KeyToP2PKScriptFactory constructor.
     * @param PublicKeySerializerInterface|null $pubKeySerializer
     */
    public function __construct(PublicKeySerializerInterface $pubKeySerializer = null)
    {
        if (null === $pubKeySerializer) {
            $pubKeySerializer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true);
        }

        $this->pubKeySerializer = $pubKeySerializer;
    }

    /**
     * @param PublicKeyInterface ...$keys
     * @return ScriptAndSignData
     */
    abstract protected function convertKeyToScriptData(PublicKeyInterface ...$keys): ScriptAndSignData;

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $pubs = [];
        foreach ($keys as $key) {
            if ($key instanceof PrivateKeyInterface) {
                $key = $key->getPublicKey();
            }
            $pubs[] = $key;
        }

        return $this->convertKeyToScriptData(...$pubs);
    }
}