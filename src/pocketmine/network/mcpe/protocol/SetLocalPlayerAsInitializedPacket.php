<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class SetLocalPlayerAsInitializedPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_LOCAL_PLAYER_AS_INITIALIZED_PACKET;

	/** @var int */
	public $entityRuntimeId;

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetLocalPlayerAsInitialized($this);
	}
}
