<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class RemoveActorPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::REMOVE_ACTOR_PACKET;

	/** @var int */
	public $entityUniqueId;

	protected function decodePayload() : void
	{
		$this->entityUniqueId = $this->getEntityUniqueId();
	}

	protected function encodePayload() : void
	{
		$this->putEntityUniqueId($this->entityUniqueId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRemoveActor($this);
	}
}
