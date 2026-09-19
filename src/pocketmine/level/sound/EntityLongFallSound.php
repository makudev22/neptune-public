<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

final class EntityLongFallSound extends Sound
{
	public function __construct(
		Vector3 $pos,
		private Entity $entity
	) {
		parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function encode()
	{
		return [LevelSoundEventPacket::create(
			LevelSoundEventPacket::SOUND_FALL_BIG,
			$this,
			-1,
			($this->entity instanceof Player ?
				"minecraft:player" :
				(AddActorPacket::LEGACY_ID_MAP_BC[$this->entity::NETWORK_ID] ?? ":")), //TODO: bad hack, stuff depends on players having a -1 network ID :(
			false, //TODO: is isBaby relevant here?
			false,
			$this->entity->getId(),
			null
		)];
	}
}
