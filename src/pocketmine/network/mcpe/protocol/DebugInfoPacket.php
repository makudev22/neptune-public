<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class DebugInfoPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::DEBUG_INFO_PACKET;

	public int $entityUniqueId;
	public string $data;

	public static function create(int $entityUniqueId, string $data) : self
	{
		$result = new self();
		$result->entityUniqueId = $entityUniqueId;
		$result->data = $data;
		return $result;
	}

	/**
	 * TODO: we can't call this getEntityRuntimeId() because of base class collision (crap architecture, thanks Shoghi)
	 */
	public function getEntityUniqueIdField() : int
	{
		return $this->entityUniqueId;
	}

	public function getData() : string
	{
		return $this->data;
	}

	protected function decodePayload() : void
	{
		$this->entityUniqueId = $this->getEntityUniqueId();
		$this->data = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putEntityUniqueId($this->entityUniqueId);
		$this->putString($this->data);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleDebugInfo($this);
	}
}
