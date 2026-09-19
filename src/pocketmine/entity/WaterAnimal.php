<?php


declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageEvent;

abstract class WaterAnimal extends Mob implements Ageable
{
	public const SPAWN_PLACEMENT_TYPE = SpawnPlacementTypes::PLACEMENT_TYPE_IN_WATER;

	public function isBaby() : bool
	{
		return $this->getGenericFlag(self::DATA_FLAG_BABY);
	}

	public function canBreathe() : bool
	{
		return $this->isUnderwater();
	}

	public function canSpawnHere() : bool
	{
		return true;
	}

	public function onAirExpired() : void
	{
		$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_SUFFOCATION, 2);
		$this->attack($ev);
	}
}
