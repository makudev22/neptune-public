<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class RespawnPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::RESPAWN_PACKET;

	public const SEARCHING_FOR_SPAWN = 0;
	public const READY_TO_SPAWN = 1;
	public const CLIENT_READY_TO_SPAWN = 2;

	/** @var Vector3 */
	public $position;
	/** @var int */
	public $respawnState = self::SEARCHING_FOR_SPAWN;
	/** @var int */
	public $entityRuntimeId;

	protected function decodePayload() : void
	{
		$this->position = $this->getVector3();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->respawnState = $this->getByte();
			$this->entityRuntimeId = $this->getEntityRuntimeId();
		}
	}

	protected function encodePayload() : void
	{
		$this->putVector3($this->position);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putByte($this->respawnState);
			$this->putEntityRuntimeId($this->entityRuntimeId);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRespawn($this);
	}
}
