<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class RemoveVolumeEntityPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::REMOVE_VOLUME_ENTITY_PACKET;

	public int $entityNetId;
	public int $dimension;

	public static function create(int $entityNetId, int $dimension) : self
	{
		$result = new self();
		$result->entityNetId = $entityNetId;
		$result->dimension = $dimension;
		return $result;
	}

	public function getEntityNetId() : int
	{
		return $this->entityNetId;
	}

	public function getDimension() : int
	{
		return $this->dimension;
	}

	protected function decodePayload() : void
	{
		$this->entityNetId = $this->getUnsignedVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_503) {
			$this->dimension = $this->getVarInt();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->entityNetId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_503) {
			$this->putVarInt($this->dimension);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRemoveVolumeEntity($this);
	}
}
