<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Peer;

use Raptorio\Blockchain\P2P\Ip\IpInterface;
use Raptorio\Blockchain\P2P\Ip\Ipv4;
use Raptorio\Blockchain\P2P\Messages\Factory as MsgFactory;
use Raptorio\Blockchain\P2P\Messages\Version;
use Raptorio\Blockchain\P2P\Services;
use Raptorio\Blockchain\P2P\Structure\NetworkAddress;
use Raptorio\Blockchain\P2P\Structure\NetworkAddressInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class ConnectionParams
{
    protected $defaultUserAgent = 'raptoreum-php';
    protected $defaultProtocolVersion = 70219;
    protected $defaultTxRelay = false;
    protected $defaultBlockHeight = 0;
    protected $defaultLocalIp = '0.0.0.0';
    protected $defaultLocalPort = 0;

    /**
     * @var int|null
     */
    private $protocolVersion;

    /**
     * @var int|null
     */
    private $timestamp;

    /**
     * @var bool|null
     */
    private $txRelay;

    /**
     * @var callable|null
     */
    private $bestBlockHeightCallback;

    /**
     * @var int|null
     */
    private $bestBlockHeight;

    /**
     * @var IpInterface|null
     */
    private $localIp;

    /**
     * @var int|null
     */
    private $localPort;

    /**
     * @var int|null
     */
    private $localServices;

    /**
     * @var BufferInterface|null
     */
    private $userAgent;

    /**
     * @var int
     */
    private $requiredServices = 0;

    /**
     * @param bool $optRelay
     * @return $this
     */
    public function requestTxRelay(bool $optRelay = true)
    {
        $this->txRelay = $optRelay;
        return $this;
    }

    /**
     * @param int $blockHeight
     * @return $this
     */
    public function setBestBlockHeight(int $blockHeight)
    {
        $this->bestBlockHeight = $blockHeight;
        return $this;
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function setBestBlockHeightCallback(callable $callable)
    {
        $this->bestBlockHeightCallback = $callable;
        return $this;
    }

    /**
     * @param int $version
     * @return $this
     */
    public function setProtocolVersion(int $version)
    {
        $this->protocolVersion = $version;
        return $this;
    }

    /**
     * @param IpInterface $ip
     * @return $this
     */
    public function setLocalIp(IpInterface $ip)
    {
        $this->localIp = $ip;
        return $this;
    }

    /**
     * @param int $port
     * @return $this
     */
    public function setLocalPort(int $port)
    {
        $this->localPort = $port;
        return $this;
    }

    /**
     * @param int $services
     * @return $this
     */
    public function setLocalServices(int $services)
    {
        $this->localServices = $services;
        return $this;
    }

    /**
     * @param NetworkAddressInterface $networkAddress
     * @return $this
     */
    public function setLocalNetAddr(NetworkAddressInterface $networkAddress)
    {
        // @todo: just set net addr?
        $this->setLocalIp($networkAddress->getIp());
        $this->setLocalPort($networkAddress->getPort());
        $this->setLocalServices($networkAddress->getServices());
        return $this;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setTimestamp(int $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @param int $services
     * @return $this
     */
    public function setRequiredServices(int $services)
    {
        $this->requiredServices = $services;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequiredServices(): int
    {
        return $this->requiredServices;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function setUserAgent(string $string)
    {
        $this->userAgent = new Buffer($string);
        return $this;
    }

    /**
     * @param MsgFactory $messageFactory
     * @param NetworkAddress $remoteAddress
     * @return Version
     */
    public function produceVersion(MsgFactory $messageFactory, NetworkAddress $remoteAddress): Version
    {
        $protocolVersion = is_null($this->protocolVersion) ? $this->defaultProtocolVersion : $this->protocolVersion;
        $localServices = is_null($this->localServices) ? Services::NONE : $this->localServices;
        $timestamp = is_null($this->timestamp) ? time() : $this->timestamp;
        $localAddr = new NetworkAddress(
            $localServices,
            is_null($this->localIp) ? new Ipv4($this->defaultLocalIp) : $this->localIp,
            is_null($this->localPort) ? $this->defaultLocalPort : $this->localPort
        );

        $userAgent = is_null($this->userAgent) ? new Buffer($this->defaultUserAgent) : $this->userAgent;

        if (is_callable($this->bestBlockHeightCallback)) {
            $cb = $this->bestBlockHeightCallback;
            $bestHeight = $cb();
        } elseif (!is_null($this->bestBlockHeight)) {
            $bestHeight = $this->bestBlockHeight;
        } else {
            $bestHeight = $this->defaultBlockHeight;
        }

        $relay = is_null($this->txRelay) ? $this->defaultTxRelay : $this->txRelay;

        return $messageFactory->version($protocolVersion, $localServices, $timestamp, $remoteAddress, $localAddr, $userAgent, $bestHeight, $relay);
    }
}
