<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript\Decorator;

use Raptorio\Blockchain\Crypto\EcAdapter\Key\KeyInterface;
use Raptorio\Blockchain\Key\KeyToScript\ScriptAndSignData;
use Raptorio\Blockchain\Script\P2shScript;
use Raptorio\Blockchain\Script\ScriptType;
use Raptorio\Blockchain\Transaction\Factory\SignData;

class P2shScriptDecorator extends ScriptHashDecorator
{
    /**
     * @var array
     */
    protected $allowedScriptTypes = [
        ScriptType::MULTISIG,
        ScriptType::P2PKH,
        ScriptType::P2PK,
        ScriptType::P2WKH,
    ];

    /**
     * @var string
     */
    protected $decorateType = ScriptType::P2SH;

    /**
     * @param KeyInterface ...$keys
     * @return ScriptAndSignData
     * @throws \Raptorio\Blockchain\Exceptions\P2shScriptException
     */
    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $redeemScript = new P2shScript($this->scriptDataFactory->convertKey(...$keys)->getScriptPubKey());
        return new ScriptAndSignData(
            $redeemScript->getOutputScript(),
            (new SignData())
                ->p2sh($redeemScript)
        );
    }
}
