<?php

declare(strict_types=1);

namespace Raptorio\Blockchain\P2P\Serializer;

use Raptorio\Blockchain\Bitcoin;
use Raptorio\Blockchain\Crypto\Hash;
use Raptorio\Blockchain\Network\NetworkInterface;
use Raptorio\Blockchain\P2P\Message;
use Raptorio\Blockchain\P2P\Messages\Block;
use Raptorio\Blockchain\P2P\Messages\FilterClear;
use Raptorio\Blockchain\P2P\Messages\GetAddr;
use Raptorio\Blockchain\P2P\Messages\MemPool;
use Raptorio\Blockchain\P2P\Messages\SendHeaders;
use Raptorio\Blockchain\P2P\Messages\Tx;
use Raptorio\Blockchain\P2P\Messages\VerAck;
use Raptorio\Blockchain\P2P\NetworkMessage;
use Raptorio\Blockchain\P2P\Serializer\Message\AddrSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\AlertSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\FeeFilterSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\FilterAddSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\FilterLoadSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\GetBlocksSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\GetDataSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\GetHeadersSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\HeadersSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\InvSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\MerkleBlockSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\NotFoundSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\PingSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\PongSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\RejectSerializer;
use Raptorio\Blockchain\P2P\Serializer\Message\VersionSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\AlertDetailSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\HeaderSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\InventorySerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\NetworkAddressSerializer;
use Raptorio\Blockchain\P2P\Serializer\Structure\NetworkAddressTimestampSerializer;
use Raptorio\Blockchain\P2P\Structure\Header;
use Raptorio\Blockchain\Serializer\Block\BlockHeaderSerializer;
use Raptorio\Blockchain\Serializer\Block\BlockSerializer;
use Raptorio\Blockchain\Serializer\Block\FilteredBlockSerializer;
use Raptorio\Blockchain\Serializer\Block\PartialMerkleTreeSerializer;
use Raptorio\Blockchain\Serializer\Bloom\BloomFilterSerializer;
use Raptorio\Blockchain\Serializer\Chain\BlockLocatorSerializer;
use Raptorio\Blockchain\Serializer\Transaction\TransactionSerializer;
use Raptorio\Blockchain\Serializer\Types;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

class NetworkMessageSerializer
{
    /**
     * @var NetworkInterface
     */
    private $network;

    /**
     * @var \Raptorio\Blockchain\Math\Math
     */
    private $math;

    /**
     * @var TransactionSerializer
     */
    private $txSerializer;

    /**
     * @var BlockHeaderSerializer
     */
    private $headerSerializer;

    /**
     * @var GetDataSerializer
     */
    private $getDataSerializer;

    /**
     * @var InvSerializer
     */
    private $invSerializer;

    /**
     * @var BlockSerializer
     */
    private $blockSerializer;

    /**
     * @var FilteredBlockSerializer
     */
    private $filteredBlockSerializer;

    /**
     * @var HeadersSerializer
     */
    private $headersSerializer;

    /**
     * @var FilterAddSerializer
     */
    private $filterAddSerializer;

    /**
     * @var FilterLoadSerializer
     */
    private $filterLoadSerializer;

    /**
     * @var MerkleBlockSerializer
     */
    private $merkleBlockSerializer;

    /**
     * @var PingSerializer
     */
    private $pingSerializer;

    /**
     * @var AlertSerializer
     */
    private $alertSerializer;

    /**
     * @var InventorySerializer
     */
    private $inventorySerializer;

    /**
     * @var NotFoundSerializer
     */
    private $notFoundSerializer;

    /**
     * @var RejectSerializer
     */
    private $rejectSerializer;

    /**
     * @var BlockLocatorSerializer
     */
    private $blockLocatorSerializer;

    /**
     * @var GetBlocksSerializer
     */
    private $getBlocksSerializer;

    /**
     * @var GetHeadersSerializer
     */
    private $getHeadersSerializer;

    /**
     * @var PongSerializer
     */
    private $pongSerializer;

    /**
     * @var VersionSerializer
     */
    private $versionSerializer;

    /**
     * @var FeeFilterSerializer
     */
    private $feeFilterSerializer;

    /**
     * @var AddrSerializer
     */
    private $addrSerializer;

    /**
     * @var \BitWasp\Buffertools\Types\ByteString
     */
    private $bs4le;

    /**
     * @var HeaderSerializer
     */
    private $packetHeaderSerializer;

    /**
     * @param NetworkInterface $network
     */
    public function __construct(NetworkInterface $network)
    {
        $this->math = Bitcoin::getMath();
        $this->network = $network;
        $this->bs4le = Types::bytestringle(4);
        $this->txSerializer = new TransactionSerializer();
        $this->headerSerializer = new BlockHeaderSerializer();
        $this->blockSerializer = new BlockSerializer($this->math, $this->headerSerializer, $this->txSerializer);
        $this->filteredBlockSerializer = new FilteredBlockSerializer($this->headerSerializer, new PartialMerkleTreeSerializer());
        $this->headersSerializer = new HeadersSerializer();
        $this->filterAddSerializer = new FilterAddSerializer();
        $this->filterLoadSerializer = new FilterLoadSerializer(new BloomFilterSerializer());
        $this->merkleBlockSerializer = new MerkleBlockSerializer($this->filteredBlockSerializer);
        $this->pingSerializer = new PingSerializer();
        $this->pongSerializer = new PongSerializer();
        $this->alertSerializer = new AlertSerializer(new AlertDetailSerializer());
        $this->inventorySerializer = new InventorySerializer();
        $this->getDataSerializer = new GetDataSerializer($this->inventorySerializer);
        $this->invSerializer = new InvSerializer($this->inventorySerializer);
        $this->notFoundSerializer = new NotFoundSerializer($this->inventorySerializer);
        $this->feeFilterSerializer = new FeeFilterSerializer();
        $this->rejectSerializer = new RejectSerializer();
        $this->blockLocatorSerializer = new BlockLocatorSerializer();
        $this->getBlocksSerializer = new GetBlocksSerializer($this->blockLocatorSerializer);
        $this->getHeadersSerializer = new GetHeadersSerializer($this->blockLocatorSerializer);
        $this->versionSerializer = new VersionSerializer(new NetworkAddressSerializer());
        $this->addrSerializer = new AddrSerializer(new NetworkAddressTimestampSerializer());
        $this->packetHeaderSerializer = new HeaderSerializer();
    }

    /**
     * @param Parser $parser
     * @return Header
     */
    public function parseHeader(Parser $parser): Header
    {
        $prefix = $this->bs4le->read($parser);
        if ($prefix->getHex() !== $this->network->getNetMagicBytes()) {
            throw new \RuntimeException('Invalid magic bytes for network');
        }

        return $this->packetHeaderSerializer->fromParser($parser);
    }

    /**
     * @param Header $header
     * @param Parser $parser
     * @return NetworkMessage
     */
    public function parsePacket(Header $header, Parser $parser)
    {
        $buffer = $header->getLength() > 0
            ? $parser->readBytes($header->getLength())
            : new Buffer('', 0);

        // Compare payload checksum against header value
        if (!Hash::sha256d($buffer)->slice(0, 4)->equals($header->getChecksum())) {
            throw new \RuntimeException('Invalid packet checksum');
        }

        $cmd = trim($header->getCommand());
        switch ($cmd) {
            case Message::VERSION:
                $payload = $this->versionSerializer->parse($buffer);
                break;
            case Message::VERACK:
                $payload = new VerAck();
                break;
            case Message::SENDHEADERS:
                $payload = new SendHeaders();
                break;
            case Message::ADDR:
                $payload = $this->addrSerializer->parse($buffer);
                break;
            case Message::INV:
                $payload = $this->invSerializer->parse($buffer);
                break;
            case Message::GETDATA:
                $payload = $this->getDataSerializer->parse($buffer);
                break;
            case Message::NOTFOUND:
                $payload = $this->notFoundSerializer->parse($buffer);
                break;
            case Message::GETBLOCKS:
                $payload = $this->getBlocksSerializer->parse($buffer);
                break;
            case Message::GETHEADERS:
                $payload = $this->getHeadersSerializer->parse($buffer);
                break;
            case Message::TX:
                $payload = new Tx($buffer);
                break;
            case Message::BLOCK:
                $payload = new Block($buffer);
                break;
            case Message::HEADERS:
                $payload = $this->headersSerializer->parse($buffer);
                break;
            case Message::GETADDR:
                $payload = new GetAddr();
                break;
            case Message::MEMPOOL:
                $payload = new MemPool();
                break;
            case Message::FEEFILTER:
                $payload = $this->feeFilterSerializer->parse($buffer);
                break;
            case Message::FILTERLOAD:
                $payload = $this->filterLoadSerializer->parse($buffer);
                break;
            case Message::FILTERADD:
                $payload = $this->filterAddSerializer->parse($buffer);
                break;
            case Message::FILTERCLEAR:
                $payload = new FilterClear();
                break;
            case Message::MERKLEBLOCK:
                $payload = $this->merkleBlockSerializer->parse($buffer);
                break;
            case Message::PING:
                $payload = $this->pingSerializer->parse($buffer);
                break;
            case Message::PONG:
                $payload = $this->pongSerializer->parse($buffer);
                break;
            case Message::REJECT:
                $payload = $this->rejectSerializer->parse($buffer);
                break;
            case Message::ALERT:
                $payload = $this->alertSerializer->parse($buffer);
                break;
            default:
                throw new \RuntimeException('Unsupported message type');
        }

        return new NetworkMessage($this->network, $payload);
    }

    /**
     * @param Parser $parser
     * @return NetworkMessage
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     * @throws \Exception
     */
    public function fromParser(Parser $parser): NetworkMessage
    {
        $header = $this->parseHeader($parser);
        return $this->parsePacket($header, $parser);
    }

    /**
     * @param NetworkMessage $object
     * @return BufferInterface
     */
    public function serialize(NetworkMessage $object): BufferInterface
    {
        $prefix = $this->bs4le->write(Buffer::hex($this->network->getNetMagicBytes()));
        $header = $this->packetHeaderSerializer->serialize($object->getHeader());
        $payload = $object->getPayload()->getBuffer();

        return new Buffer("{$prefix}{$header->getBinary()}{$payload->getBinary()}");
    }

    /**
     * @param BufferInterface $data
     * @return NetworkMessage
     * @throws \Exception
     */
    public function parse(BufferInterface $data): NetworkMessage
    {
        return $this->fromParser(new Parser($data));
    }
}
