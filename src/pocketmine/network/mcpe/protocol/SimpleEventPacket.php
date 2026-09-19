<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SimpleEventPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SIMPLE_EVENT_PACKET;

	public const TYPE_ENABLE_COMMANDS = 1;
	public const TYPE_DISABLE_COMMANDS = 2;

	/** @var int */
	public $eventType;

	protected function decodePayload() : void
	{
		$this->eventType = $this->getLShort();
	}

	protected function encodePayload() : void
	{
		$this->putLShort($this->eventType);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSimpleEvent($this);
	}
}
