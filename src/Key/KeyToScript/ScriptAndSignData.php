<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Key\KeyToScript;

use Raptorio\Blockchain\Address\Address;
use Raptorio\Blockchain\Address\BaseAddressCreator;
use Raptorio\Blockchain\Script\ScriptInterface;
use Raptorio\Blockchain\Transaction\Factory\SignData;

class ScriptAndSignData
{
    /**
     * @var ScriptInterface
     */
    private $scriptPubKey;

    /**
     * @var SignData
     */
    private $signData;

    /**
     * ScriptAndSignData constructor.
     * @param ScriptInterface $scriptPubKey
     * @param SignData $signData
     */
    public function __construct(ScriptInterface $scriptPubKey, SignData $signData)
    {
        $this->scriptPubKey = $scriptPubKey;
        $this->signData = $signData;
    }

    /**
     * @return ScriptInterface
     */
    public function getScriptPubKey(): ScriptInterface
    {
        return $this->scriptPubKey;
    }

    /**
     * @param BaseAddressCreator $creator
     * @return Address
     */
    public function getAddress(BaseAddressCreator $creator): Address
    {
        return $creator->fromOutputScript($this->scriptPubKey);
    }

    /**
     * @return SignData
     */
    public function getSignData(): SignData
    {
        return $this->signData;
    }
}
