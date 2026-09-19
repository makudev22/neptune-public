<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\DimensionIds;

class SpawnParticleEffectPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SPAWN_PARTICLE_EFFECT_PACKET;

	/** @var int */
	public $dimensionId = DimensionIds::OVERWORLD; //wtf mojang
	/** @var int */
	public $entityUniqueId = -1; //default none
	/** @var Vector3 */
	public $position;
	/** @var string */
	public $particleName;
	/** @var string */
	public $molangVariablesJson = "";

	protected function decodePayload() : void
	{
		$this->dimensionId = $this->getByte();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->entityUniqueId = $this->getEntityUniqueId();
		}
		$this->position = $this->getVector3();
		$this->particleName = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_503) {
			$this->molangVariablesJson = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->dimensionId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putEntityUniqueId($this->entityUniqueId);
		}
		$this->putVector3($this->position);
		$this->putString($this->particleName);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_503) {
			$this->putString($this->molangVariablesJson);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSpawnParticleEffect($this);
	}
}
