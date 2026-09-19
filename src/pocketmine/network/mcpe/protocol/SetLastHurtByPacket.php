<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetLastHurtByPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_LAST_HURT_BY_PACKET;

	/** @var int */
	public $entityTypeId;

	protected function decodePayload() : void
	{
		$this->entityTypeId = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->entityTypeId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetLastHurtBy($this);
	}
}
