<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ClientToServerHandshakePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CLIENT_TO_SERVER_HANDSHAKE_PACKET;

	public function canBeSentBeforeLogin() : bool
	{
		return true;
	}

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
		return $session->handleClientToServerHandshake($this);
	}
}
