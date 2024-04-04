<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Settings;

use Raptorio\Blockchain\P2P\DnsSeeds\TestNetDnsSeeds;

class Testnet3Settings extends NetworkSettings
{
    protected $defaultP2PPort = 10229;

    public function __construct()
    {
        $this->dnsSeeds = new TestNetDnsSeeds();
    }
}
