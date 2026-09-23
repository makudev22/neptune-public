<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\SubChunkPosition;
use pocketmine\network\mcpe\protocol\types\SubChunkPositionOffset;

use function count;

class SubChunkRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SUB_CHUNK_REQUEST_PACKET;
	private const MAX_ENTRIES = 256;

	private int $dimension;
	private SubChunkPosition $basePosition;
	/**
	 * @var SubChunkPositionOffset[]
	 * @phpstan-var list<SubChunkPositionOffset>
	 */
	private array $entries = [];

	/**
	 * @generate-create-func
	 * @param SubChunkPositionOffset[] $entries
	 * @phpstan-param list<SubChunkPositionOffset> $entries
	 */
	public static function create(int $dimension, SubChunkPosition $basePosition, array $entries) : self
	{
		$result = new self();
		$result->dimension = $dimension;
		$result->basePosition = $basePosition;
		$result->entries = $entries;
		return $result;
	}

	public function getDimension() : int
	{
		return $this->dimension;
	}

	public function getBasePosition() : SubChunkPosition
	{
		return $this->basePosition;
	}

	/**
	 * @return SubChunkPositionOffset[]
	 * @phpstan-return list<SubChunkPositionOffset>
	 */
	public function getEntries() : array
	{
		return $this->entries;
	}

	protected function decodePayload() : void
	{
		$this->dimension = $this->getVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_1001) {
			$count = $this->getUnsignedVarInt();
			if ($count > self::MAX_ENTRIES) {
				throw new PacketDecodeException("Too many subchunk request entries: $count");
			}
			for ($i = 0; $i < $count; $i++) {
				$this->entries[] = SubChunkPositionOffset::read($this);
			}

			$this->basePosition = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? SubChunkPosition::readVarInts($this) : SubChunkPosition::readFixedInts($this);
		} else {
			$this->basePosition = SubChunkPosition::readVarInts($this);

			if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
				for ($i = 0, $count = $this->getLInt(); $i < $count; $i++) {
					$this->entries[] = SubChunkPositionOffset::read($this);
				}
			}
		}
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->dimension);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_1001) {
			$this->putUnsignedVarInt(count($this->entries));
			foreach ($this->entries as $entry) {
				$entry->write($this);
			}

			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->basePosition->writeVarInts($this);
			} else {
				$this->basePosition->writeFixedInts($this);
			}
		} else {
			$this->basePosition->writeVarInts($this);

			if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
				$this->putLInt(count($this->entries));
				foreach ($this->entries as $entry) {
					$entry->write($this);
				}
			}
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSubChunkRequest($this);
	}
}
