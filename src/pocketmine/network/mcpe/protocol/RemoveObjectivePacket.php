<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class RemoveObjectivePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::REMOVE_OBJECTIVE_PACKET;

	/** @var string */
	public $objectiveName;

	protected function decodePayload() : void
	{
		$this->objectiveName = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->objectiveName);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRemoveObjective($this);
	}
}
