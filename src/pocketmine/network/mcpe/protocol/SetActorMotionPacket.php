<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class SetActorMotionPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_ACTOR_MOTION_PACKET;

	/** @var int */
	public $entityRuntimeId;
	/** @var Vector3 */
	public $motion;
	/** @var int */
	public $tick;

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->motion = $this->getVector3();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_662) {
			$this->tick = $this->getUnsignedVarLong();
		}
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putVector3($this->motion);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_662) {
			$this->putUnsignedVarLong($this->tick);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetActorMotion($this);
	}
}
