<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ScriptCustomEventPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SCRIPT_CUSTOM_EVENT_PACKET;

	/** @var string */
	public $eventName;
	/** @var string json data */
	public $eventData;

	protected function decodePayload() : void
	{
		$this->eventName = $this->getString();
		$this->eventData = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->eventName);
		$this->putString($this->eventData);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleScriptCustomEvent($this);
	}
}
