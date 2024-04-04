<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Ip;

use BitWasp\Buffertools\SerializableInterface;

interface IpInterface extends SerializableInterface
{
    public function getHost(): string;
}
