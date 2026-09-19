<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetDefaultGameTypePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_DEFAULT_GAME_TYPE_PACKET;

	/** @var int */
	public $gamemode;

	protected function decodePayload() : void
	{
		$this->gamemode = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->gamemode);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetDefaultGameType($this);
	}
}
