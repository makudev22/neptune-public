<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ActorPickRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ACTOR_PICK_REQUEST_PACKET;

	public int $entityUniqueId;
	public int $hotbarSlot;
	public bool $addUserData = true;

	/**
	 * @generate-create-func
	 */
	public static function create(int $entityUniqueId, int $hotbarSlot, bool $addUserData) : self
	{
		$result = new self();
		$result->entityUniqueId = $entityUniqueId;
		$result->hotbarSlot = $hotbarSlot;
		$result->addUserData = $addUserData;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->entityUniqueId = $this->getLLong();
		$this->hotbarSlot = $this->getByte();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$this->addUserData = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putLLong($this->entityUniqueId);
		$this->putByte($this->hotbarSlot);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$this->putBool($this->addUserData);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleActorPickRequest($this);
	}
}
