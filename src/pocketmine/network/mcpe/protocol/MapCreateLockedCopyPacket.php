<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class MapCreateLockedCopyPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::MAP_CREATE_LOCKED_COPY_PACKET;

	/** @var int */
	public $originalMapId;
	/** @var int */
	public $newMapId;

	protected function decodePayload() : void
	{
		$this->originalMapId = $this->getEntityUniqueId();
		$this->newMapId = $this->getEntityUniqueId();
	}

	protected function encodePayload() : void
	{
		$this->putEntityUniqueId($this->originalMapId);
		$this->putEntityUniqueId($this->newMapId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleMapCreateLockedCopy($this);
	}
}
