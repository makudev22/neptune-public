<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetHealthPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_HEALTH_PACKET;

	/** @var int */
	public $health;

	protected function decodePayload() : void
	{
		$this->health = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->health);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetHealth($this);
	}
}
