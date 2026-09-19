<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class SpawnExperienceOrbPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET;

	/** @var Vector3 */
	public $position;
	/** @var int */
	public $amount;

	protected function decodePayload() : void
	{
		$this->position = $this->getVector3();
		$this->amount = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVector3($this->position);
		$this->putVarInt($this->amount);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSpawnExperienceOrb($this);
	}
}
