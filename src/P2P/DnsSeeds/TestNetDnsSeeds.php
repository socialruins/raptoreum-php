<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\DnsSeeds;

class TestNetDnsSeeds extends DnsSeedList
{
    public function __construct()
    {
        parent::__construct([
            'lbdn.raptoreum.com',
            '47.155.87.132',
        ]);
    }
}
