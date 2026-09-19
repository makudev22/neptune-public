<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ActorFallPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ACTOR_FALL_PACKET;

	public int $entityRuntimeId;
	public float $fallDistance;
	public bool $isInVoid;

	/**
	 * @generate-create-func
	 */
	public static function create(int $entityRuntimeId, float $fallDistance, bool $isInVoid) : self
	{
		$result = new self();
		$result->entityRuntimeId = $entityRuntimeId;
		$result->fallDistance = $fallDistance;
		$result->isInVoid = $isInVoid;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->fallDistance = $this->getLFloat();
		$this->isInVoid = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putLFloat($this->fallDistance);
		$this->putBool($this->isInVoid);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleActorFall($this);
	}
}
