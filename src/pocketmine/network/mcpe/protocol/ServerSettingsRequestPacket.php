<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ServerSettingsRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVER_SETTINGS_REQUEST_PACKET;

	protected function decodePayload() : void
	{
		//No payload
	}

	protected function encodePayload() : void
	{
		//No payload
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerSettingsRequest($this);
	}
}
