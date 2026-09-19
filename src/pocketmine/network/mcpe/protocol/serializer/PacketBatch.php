<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\serializer;

use pocketmine\network\mcpe\convert\PacketIdTranslator;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\UnknownPacket;
use pocketmine\utils\BinaryDataException;
use pocketmine\utils\BinaryStream;

use function strlen;

class PacketBatch
{
	private function __construct()
	{
		//NOOP
	}

	/**
	 * @phpstan-return \Generator<int, string, void, void>
	 * @throws PacketDecodeException
	 */
	final public static function decodeRaw(BinaryStream $stream) : \Generator
	{
		$c = 0;
		while (!$stream->feof()) {
			try {
				$length = $stream->getUnsignedVarInt();
				$buffer = $stream->get($length);
			} catch (BinaryDataException $e) {
				throw new PacketDecodeException("Error decoding packet $c in batch: " . $e->getMessage(), 0, $e);
			}
			yield $buffer;
			$c++;
		}
	}

	/**
	 * @param string[] $packets
	 * @phpstan-param list<string> $packets
	 */
	final public static function encodeRaw(BinaryStream $stream, array $packets) : void
	{
		foreach ($packets as $packet) {
			$stream->putUnsignedVarInt(strlen($packet));
			$stream->put($packet);
		}
	}

	/**
	 * @phpstan-return \Generator<int, DataPacket, void, void>
	 * @throws PacketDecodeException
	 */
	final public static function decodePackets(BinaryStream $stream, int $protocolVersion) : \Generator
	{
		$c = 0;
		foreach (self::decodeRaw($stream) as $packetBuffer) {
			$packet = PacketPool::getPacket($packetBuffer, $protocolVersion);
			$packet->setProtocol($protocolVersion);
			if (!($packet instanceof UnknownPacket)) {
				try {
					$packet->decode();
				} catch (PacketDecodeException $e) {
					throw new PacketDecodeException("Error decoding packet $c in batch: " . $e->getMessage(), 0, $e);
				}
				yield $packet;
			} else {
				throw new PacketDecodeException("Unknown packet $c in batch");
			}
			$c++;
		}
	}

	/**
	 * @param DataPacket[] $packets
	 * @phpstan-param list<DataPacket> $packets
	 */
	final public static function encodePackets(BinaryStream $stream, array $packets, int $protocolVersion) : void
	{
		foreach ($packets as $packet) {
			if (PacketIdTranslator::getInstance()->toNetworkId($protocolVersion, $packet->pid()) === null) {
				continue;
			}

			$packet->setProtocol($protocolVersion);
			$packet->encode();
			$stream->putUnsignedVarInt(strlen($packet->getBuffer()));
			$stream->put($packet->getBuffer());
		}
	}
}
