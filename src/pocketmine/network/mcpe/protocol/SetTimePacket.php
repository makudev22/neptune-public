<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetTimePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_TIME_PACKET;

	public int $time;

	protected function decodePayload() : void
	{
		$this->time = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->time);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetTime($this);
	}
}
