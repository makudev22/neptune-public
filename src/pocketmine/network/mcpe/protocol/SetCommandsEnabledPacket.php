<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetCommandsEnabledPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_COMMANDS_ENABLED_PACKET;

	/** @var bool */
	public $enabled;

	protected function decodePayload() : void
	{
		$this->enabled = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putBool($this->enabled);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetCommandsEnabled($this);
	}
}
