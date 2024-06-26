<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\Transaction;

use Raptorio\Blockchain\Script\ScriptInterface;
use Raptorio\Blockchain\SerializableInterface;

interface TransactionOutputInterface extends SerializableInterface
{
    /**
     * Get the value of this output
     *
     * @return int
     */
    public function getValue(): int;

    /**
     * Get the script for this output
     *
     * @return ScriptInterface
     */
    public function getScript(): ScriptInterface;

    /**
     * @param TransactionOutputInterface $output
     * @return bool
     */
    public function equals(TransactionOutputInterface $output): bool;
}
