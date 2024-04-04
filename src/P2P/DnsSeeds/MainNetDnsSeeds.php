<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\DnsSeeds;

class MainNetDnsSeeds extends DnsSeedList
{
    public function __construct()
    {
        parent::__construct([
            'lbdn.raptoreum.com',
            '51.89.21.112',
        ]);
    }
}
