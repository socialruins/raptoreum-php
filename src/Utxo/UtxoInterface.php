<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Utxo;

use Raptorio\Blockchain\Transaction\OutPointInterface;
use Raptorio\Blockchain\Transaction\TransactionOutputInterface;

interface UtxoInterface
{
    /**
     * @return OutPointInterface
     */
    public function getOutPoint(): OutPointInterface;

    /**
     * @return TransactionOutputInterface
     */
    public function getOutput(): TransactionOutputInterface;
}
