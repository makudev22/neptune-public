<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class TakeItemActorPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::TAKE_ITEM_ACTOR_PACKET;

	/** @var int */
	public $target;
	/** @var int */
	public $eid;

	protected function decodePayload() : void
	{
		$this->target = $this->getEntityRuntimeId();
		$this->eid = $this->getEntityRuntimeId();
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->target);
		$this->putEntityRuntimeId($this->eid);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleTakeItemActor($this);
	}
}
