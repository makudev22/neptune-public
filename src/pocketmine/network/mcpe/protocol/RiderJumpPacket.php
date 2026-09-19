<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class RiderJumpPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::RIDER_JUMP_PACKET;

	/** @var int */
	public $jumpStrength; //percentage

	protected function decodePayload() : void
	{
		$this->jumpStrength = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->jumpStrength);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRiderJump($this);
	}
}
