<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Script\ScriptInfo;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Crypto\EcAdapter\EcSerializer;
use Raptorio\Blockchain\Crypto\EcAdapter\Impl\PhpEcc\Key\PublicKey;
use Raptorio\Blockchain\Crypto\EcAdapter\Key\PublicKeyInterface;
use Raptorio\Blockchain\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use Raptorio\Blockchain\Script\Opcodes;
use Raptorio\Blockchain\Script\Parser\Operation;
use Raptorio\Blockchain\Script\ScriptInterface;
use Raptorio\Blockchain\Script\ScriptType;
use BitWasp\Buffertools\BufferInterface;

class Multisig
{
    /**
     * @var int
     */
    private $m;

    /**
     * @var int
     */
    private $n;

    /**
     * @var bool
     */
    private $verify = false;

    /**
     * @var BufferInterface[]
     */
    private $keyBuffers = [];

    /**
     * @var PublicKeySerializerInterface
     */
    private $pubKeySerializer;

    /**
     * Multisig constructor.
     * @param int $requiredSigs
     * @param BufferInterface[] $keys
     * @param int $opcode
     * @param bool $allowVerify
     * @param PublicKeySerializerInterface|null $pubKeySerializer
     */
    public function __construct(int $requiredSigs, array $keys, int $opcode, $allowVerify = false, PublicKeySerializerInterface $pubKeySerializer = null)
    {
        if ($opcode === Opcodes::OP_CHECKMULTISIG) {
            $verify = false;
        } else if ($allowVerify && $opcode === Opcodes::OP_CHECKMULTISIGVERIFY) {
            $verify = true;
        } else {
            throw new \InvalidArgumentException('Malformed multisig script');
        }

        foreach ($keys as $key) {
            if (!PublicKey::isCompressedOrUncompressed($key)) {
                throw new \RuntimeException("Malformed public key");
            }
        }

        $keyCount = count($keys);
        if ($requiredSigs < 0 || $requiredSigs > $keyCount) {
            throw new \RuntimeException("Invalid number of required signatures");
        }

        if ($keyCount < 1 || $keyCount > 16) {
            throw new \RuntimeException("Invalid number of public keys");
        }

        if (null === $pubKeySerializer) {
            $pubKeySerializer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true, Bitcoin::getEcAdapter());
        }

        $this->verify = $verify;
        $this->m = $requiredSigs;
        $this->n = $keyCount;
        $this->keyBuffers = $keys;
        $this->pubKeySerializer = $pubKeySerializer;
    }

    /**
     * @param Operation[] $decoded
     * @param PublicKeySerializerInterface|null $pubKeySerializer
     * @param bool $allowVerify
     * @return Multisig
     */
    public static function fromDecodedScript(array $decoded, PublicKeySerializerInterface $pubKeySerializer = null, $allowVerify = false)
    {
        if (count($decoded) < 4) {
            throw new \InvalidArgumentException('Malformed multisig script');
        }

        $mCode = $decoded[0]->getOp();
        $nCode = $decoded[count($decoded) - 2]->getOp();
        $opCode = end($decoded)->getOp();

        $requiredSigs = \Raptorio\Blockchain\Script\decodeOpN($mCode);
        $publicKeyBuffers = [];
        foreach (array_slice($decoded, 1, -2) as $key) {
            /** @var \Raptorio\Blockchain\Script\Parser\Operation $key */
            if (!$key->isPush()) {
                throw new \RuntimeException('Malformed multisig script');
            }

            $buffer = $key->getData();
            $publicKeyBuffers[] = $buffer;
        }

        $keyCount = \Raptorio\Blockchain\Script\decodeOpN($nCode);
        if ($keyCount !== count($publicKeyBuffers)) {
            throw new \LogicException('No public keys found in script');
        }

        return new Multisig($requiredSigs, $publicKeyBuffers, $opCode, $allowVerify, $pubKeySerializer);
    }

    /**
     * @param ScriptInterface $script
     * @param PublicKeySerializerInterface|null $pubKeySerializer
     * @param bool $allowVerify
     * @return Multisig
     */
    public static function fromScript(ScriptInterface $script, PublicKeySerializerInterface $pubKeySerializer = null, bool $allowVerify = false)
    {
        return static::fromDecodedScript($script->getScriptParser()->decode(), $pubKeySerializer, $allowVerify);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ScriptType::MULTISIG;
    }

    /**
     * @return int
     */
    public function getRequiredSigCount(): int
    {
        return $this->m;
    }

    /**
     * @return int
     */
    public function getKeyCount(): int
    {
        return $this->n;
    }

    /**
     * @return bool
     */
    public function isChecksigVerify(): bool
    {
        return $this->verify;
    }

    /**
     * @param PublicKeyInterface $publicKey
     * @return bool
     */
    public function checkInvolvesKey(PublicKeyInterface $publicKey): bool
    {
        $buffer = $this->pubKeySerializer->serialize($publicKey);
        foreach ($this->keyBuffers as $key) {
            if ($key->equals($buffer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array|BufferInterface[]
     */
    public function getKeyBuffers(): array
    {
        return $this->keyBuffers;
    }
}
