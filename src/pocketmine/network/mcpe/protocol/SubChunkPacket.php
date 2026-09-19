<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithCache as EntryWithBlobHash;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithCacheList as ListWithBlobHashes;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithoutCache as EntryWithoutBlobHash;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithoutCacheList as ListWithoutBlobHashes;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketHeightMapInfo;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketHeightMapType;
use pocketmine\network\mcpe\protocol\types\SubChunkPosition;

use function count;

class SubChunkPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SUB_CHUNK_PACKET;

	private int $dimension;
	private SubChunkPosition $baseSubChunkPosition;
	private string $data;
	private int $requestResult;
	private ?SubChunkPacketHeightMapInfo $heightMapData = null;
	private ?int $usedBlobHash = null;
	private ListWithBlobHashes|ListWithoutBlobHashes $entries;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $dimension,
		SubChunkPosition $baseSubChunkPosition,
		string $data,
		int $requestResult,
		?SubChunkPacketHeightMapInfo $heightMapData,
		?int $usedBlobHash,
		ListWithBlobHashes|ListWithoutBlobHashes $entries
	) : self {
		$result = new self();
		$result->dimension = $dimension;
		$result->baseSubChunkPosition = $baseSubChunkPosition;
		$result->data = $data;
		$result->requestResult = $requestResult;
		$result->heightMapData = $heightMapData;
		$result->usedBlobHash = $usedBlobHash;
		$result->entries = $entries;
		return $result;
	}

	public function isCacheEnabled() : bool
	{
		return $this->entries instanceof ListWithBlobHashes;
	}

	public function getDimension() : int
	{
		return $this->dimension;
	}

	public function getBaseSubChunkPosition() : SubChunkPosition
	{
		return $this->baseSubChunkPosition;
	}

	public function getData() : string
	{
		return $this->data;
	}

	public function getRequestResult() : int
	{
		return $this->requestResult;
	}

	public function getHeightMapData() : ?SubChunkPacketHeightMapInfo
	{
		return $this->heightMapData;
	}

	public function getUsedBlobHash() : ?int
	{
		return $this->usedBlobHash;
	}

	public function getEntries() : ListWithBlobHashes|ListWithoutBlobHashes
	{
		return $this->entries;
	}

	protected function decodePayload() : void
	{
		$cacheEnabled = true;
		if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
			$cacheEnabled = $this->getBool();
		}

		$this->dimension = $this->getVarInt();
		$this->baseSubChunkPosition = SubChunkPosition::readVarInts($this);

		if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
			$count = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getLInt();
			if ($cacheEnabled) {
				$entries = [];
				for ($i = 0; $i < $count; $i++) {
					$entries[] = EntryWithBlobHash::read($this);
				}
				$this->entries = new ListWithBlobHashes($entries);
			} else {
				$entries = [];
				for ($i = 0; $i < $count; $i++) {
					$entries[] = EntryWithoutBlobHash::read($this);
				}
				$this->entries = new ListWithoutBlobHashes($entries);
			}
		} else {
			$this->data = $this->getString();
			$this->requestResult = $this->getVarInt();
			$heightMapDataType = $this->getByte();
			$this->heightMapData = match($heightMapDataType) {
				SubChunkPacketHeightMapType::NO_DATA => null,
				SubChunkPacketHeightMapType::DATA => SubChunkPacketHeightMapInfo::read($this),
				SubChunkPacketHeightMapType::ALL_TOO_HIGH => SubChunkPacketHeightMapInfo::allTooHigh(),
				SubChunkPacketHeightMapType::ALL_TOO_LOW => SubChunkPacketHeightMapInfo::allTooLow(),
				default => throw new PacketDecodeException("Unknown heightmap data type $heightMapDataType")
			};

			if ($this->protocol >= ProtocolInfo::PROTOCOL_475) {
				$this->usedBlobHash = $this->getOptional($this->getLLong(...));
			}
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
			$this->putBool($this->entries instanceof ListWithBlobHashes);
		}

		$this->putVarInt($this->dimension);
		$this->baseSubChunkPosition->writeVarInts($this);

		if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt(count($this->entries->getEntries()));
			} else {
				$this->putLInt(count($this->entries->getEntries()));
			}

			foreach ($this->entries->getEntries() as $entry) {
				$entry->write($this);
			}
		} else {
			$this->putString($this->data);
			$this->putVarInt($this->requestResult);
			if ($this->heightMapData === null) {
				$this->putByte(SubChunkPacketHeightMapType::NO_DATA);
			} elseif ($this->heightMapData->isAllTooLow()) {
				$this->putByte(SubChunkPacketHeightMapType::ALL_TOO_LOW);
			} elseif ($this->heightMapData->isAllTooHigh()) {
				$this->putByte(SubChunkPacketHeightMapType::ALL_TOO_HIGH);
			} else {
				$heightMapData = $this->heightMapData; //avoid PHPStan purity issue
				$this->putByte(SubChunkPacketHeightMapType::DATA);
				$heightMapData->write($this);
			}

			if ($this->protocol >= ProtocolInfo::PROTOCOL_475) {
				$this->putOptional($this->usedBlobHash, $this->putLLong(...));
			}
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSubChunk($this);
	}
}
