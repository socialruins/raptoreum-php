<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript\Decorator;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\KeyInterface;
use Raptorio\Blockchain\Key\KeyToScript\ScriptAndSignData;
use Raptorio\Blockchain\Script\ScriptType;
use Raptorio\Blockchain\Script\WitnessScript;
use Raptorio\Blockchain\Transaction\Factory\SignData;

class P2wshScriptDecorator extends ScriptHashDecorator
{
    /**
     * @var array
     */
    protected $allowedScriptTypes = [
        ScriptType::MULTISIG,
        ScriptType::P2PKH,
        ScriptType::P2PK,
    ];

    /**
     * @var string
     */
    protected $decorateType = ScriptType::P2WSH;

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     * @throws \Raptorio\Blockchain\Exceptions\WitnessScriptException
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $witnessScript = new WitnessScript($this->scriptDataFactory->convertKey(...$keys)->getScriptPubKey());
        return new ScriptAndSignData(
            $witnessScript->getOutputScript(),
            (new SignData())
                ->p2wsh($witnessScript)
        );
    }
}
