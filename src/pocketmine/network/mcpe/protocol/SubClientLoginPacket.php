<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SubClientLoginPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SUB_CLIENT_LOGIN_PACKET;

	/** @var string */
	public $connectionRequestData;

	protected function decodePayload() : void
	{
		$this->connectionRequestData = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->connectionRequestData);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSubClientLogin($this);
	}
}
