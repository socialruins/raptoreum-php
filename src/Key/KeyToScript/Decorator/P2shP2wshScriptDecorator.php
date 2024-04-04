<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript\Decorator;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\KeyInterface;
use Raptorio\Blockchain\Key\KeyToScript\ScriptAndSignData;
use Raptorio\Blockchain\Script\P2shScript;
use Raptorio\Blockchain\Script\ScriptType;
use Raptorio\Blockchain\Script\WitnessScript;
use Raptorio\Blockchain\Transaction\Factory\SignData;

class P2shP2wshScriptDecorator extends ScriptHashDecorator
{
    /**
     * @var string[]
     */
    protected $allowedScriptTypes = [
        ScriptType::MULTISIG,
        ScriptType::P2PKH,
        ScriptType::P2PK,
    ];

    /**
     * @var string
     */
    protected $decorateType = "scripthash|witness_v0_scripthash";

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     * @throws \Raptorio\Blockchain\Exceptions\P2shScriptException
     * @throws \Raptorio\Blockchain\Exceptions\WitnessScriptException
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $witnessScript = new WitnessScript($this->scriptDataFactory->convertKey(...$keys)->getScriptPubKey());
        $redeemScript = new P2shScript($witnessScript);
        return new ScriptAndSignData(
            $redeemScript->getOutputScript(),
            (new SignData())
                ->p2sh($redeemScript)
                ->p2wsh($witnessScript)
        );
    }
}
