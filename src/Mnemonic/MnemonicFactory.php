<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Mnemonic;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use Raptorio\Blockchain\Mnemonic\Bip39\Bip39Mnemonic;
use Raptorio\Blockchain\Mnemonic\Bip39\Bip39WordListInterface;
use Raptorio\Blockchain\Mnemonic\Electrum\ElectrumMnemonic;
use Raptorio\Blockchain\Mnemonic\Electrum\ElectrumWordListInterface;

class MnemonicFactory
{

    /**
     * @param ElectrumWordListInterface $wordList
     * @param EcAdapterInterface $ecAdapter
     * @return ElectrumMnemonic
     */
    public static function electrum(ElectrumWordListInterface $wordList = null, EcAdapterInterface $ecAdapter = null): ElectrumMnemonic
    {
        return new ElectrumMnemonic(
            $ecAdapter ?: Bitcoin::getEcAdapter(),
            $wordList ?: new \Raptorio\Blockchain\Mnemonic\Electrum\Wordlist\EnglishWordList()
        );
    }

    /**
     * @param \Raptorio\Blockchain\Mnemonic\Bip39\Bip39WordListInterface $wordList
     * @param EcAdapterInterface $ecAdapter
     * @return Bip39Mnemonic
     */
    public static function bip39(Bip39WordListInterface $wordList = null, EcAdapterInterface $ecAdapter = null): Bip39Mnemonic
    {
        return new Bip39Mnemonic(
            $ecAdapter ?: Bitcoin::getEcAdapter(),
            $wordList ?: new Bip39\Wordlist\EnglishWordList()
        );
    }
}
