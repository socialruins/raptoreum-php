<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Settings;

use Raptorio\Blockchain\P2P\DnsSeeds\MainNetDnsSeeds;

class MainnetSettings extends NetworkSettings
{
    protected $defaultP2PPort = 10226;

    public function __construct()
    {
        $this->dnsSeeds = new MainNetDnsSeeds();
    }
}
