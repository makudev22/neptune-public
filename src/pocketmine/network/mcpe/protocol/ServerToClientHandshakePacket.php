<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ServerToClientHandshakePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVER_TO_CLIENT_HANDSHAKE_PACKET;

	public $publicKey;
	public $serverToken;

	/**
	 * @var string
	 * Server pubkey and token is contained in the JWT.
	 */
	public $jwt;

	public function canBeSentBeforeLogin() : bool
	{
		return true;
	}

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->jwt = $this->getString();
		} else {
			$this->publicKey = $this->getString();
			$this->serverToken = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putString($this->jwt);
		} else {
			$this->putString($this->publicKey);
			$this->putString($this->serverToken);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerToClientHandshake($this);
	}
}
