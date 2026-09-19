<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;

class AddVolumeEntityPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ADD_VOLUME_ENTITY_PACKET;

	public int $entityNetId;
	public CompoundTag $data;
	public string $jsonIdentifier;
	public string $instanceName;
	public int $minX;
	public int $minY;
	public int $minZ;
	public int $maxX;
	public int $maxY;
	public int $maxZ;
	public int $dimension;
	public string $engineVersion;

	public static function create(
		int $entityNetId,
		CompoundTag $data,
		string $jsonIdentifier,
		string $instanceName,
		Vector3 $minBound,
		Vector3 $maxBound,
		int $dimension,
		string $engineVersion
	) : self {
		$result = new self();
		$result->entityNetId = $entityNetId;
		$result->data = $data;
		$result->jsonIdentifier = $jsonIdentifier;
		$result->instanceName = $instanceName;
		[$result->minX, $result->minY, $result->minZ] = [$minBound->x, $minBound->y, $minBound->z];
		[$result->maxX, $result->maxY, $result->maxZ] = [$maxBound->x, $maxBound->y, $maxBound->z];
		$result->dimension = $dimension;
		$result->engineVersion = $engineVersion;
		return $result;
	}

	public function getEntityNetId() : int
	{
		return $this->entityNetId;
	}

	public function getData() : CompoundTag
	{
		return $this->data;
	}

	public function getJsonIdentifier() : string
	{
		return $this->jsonIdentifier;
	}

	public function getInstanceName() : string
	{
		return $this->instanceName;
	}

	public function getMinBound() : Vector3
	{
		return new Vector3($this->minX, $this->minY, $this->minZ);
	}

	public function getMaxBound() : Vector3
	{
		return new Vector3($this->maxX, $this->maxY, $this->maxZ);
	}

	public function getDimension() : int
	{
		return $this->dimension;
	}

	public function getEngineVersion() : string
	{
		return $this->engineVersion;
	}

	protected function decodePayload() : void
	{
		$this->entityNetId = $this->getUnsignedVarInt();
		$this->data = $this->getNbtCompoundRoot();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
				$this->jsonIdentifier = $this->getString();
				$this->instanceName = $this->getString();
				if ($this->protocol >= ProtocolInfo::PROTOCOL_503) {
					$this->getBlockPosition($this->minX, $this->minY, $this->minZ);
					$this->getBlockPosition($this->maxX, $this->maxY, $this->maxZ);
					$this->dimension = $this->getVarInt();
				}
			}
			$this->engineVersion = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->entityNetId);
		$this->put((new NetworkLittleEndianNBTStream())->write($this->data));
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_486) {
				$this->putString($this->jsonIdentifier);
				$this->putString($this->instanceName);
				if ($this->protocol >= ProtocolInfo::PROTOCOL_503) {
					$this->putBlockPosition($this->minX, $this->minY, $this->minZ);
					$this->putBlockPosition($this->maxX, $this->maxY, $this->maxZ);
					$this->putVarInt($this->dimension);
				}
			}
			$this->putString($this->engineVersion);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAddVolumeEntity($this);
	}
}
