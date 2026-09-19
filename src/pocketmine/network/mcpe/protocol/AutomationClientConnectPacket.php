<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class AutomationClientConnectPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::AUTOMATION_CLIENT_CONNECT_PACKET;

	public string $serverUri;

	/**
	 * @generate-create-func
	 */
	public static function create(string $serverUri) : self{
		$result = new self();
		$result->serverUri = $serverUri;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->serverUri = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->serverUri);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAutomationClientConnect($this);
	}
}
