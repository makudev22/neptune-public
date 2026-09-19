<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class MultiplayerSettingsPacket extends DataPacket
{ //TODO: this might be clientbound too, but unsure
	public const NETWORK_ID = ProtocolInfo::MULTIPLAYER_SETTINGS_PACKET;

	public const int ACTION_ENABLE_MULTIPLAYER = 0;
	public const int ACTION_DISABLE_MULTIPLAYER = 1;
	public const int ACTION_REFRESH_JOIN_CODE = 2;

	public int $action;

	public static function create(int $action) : self
	{
		$result = new self();
		$result->action = $action;
		return $result;
	}

	public function getAction() : int
	{
		return $this->action;
	}

	protected function decodePayload() : void
	{
		$this->action = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->action);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleMultiplayerSettings($this);
	}
}
