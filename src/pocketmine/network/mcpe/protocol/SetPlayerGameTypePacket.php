<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetPlayerGameTypePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_PLAYER_GAME_TYPE_PACKET;

	/** @var int */
	public $gamemode;

	protected function decodePayload() : void
	{
		$this->gamemode = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->gamemode);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetPlayerGameType($this);
	}
}
