<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\object\ItemEntity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\entity\Vehicle;

/**
 * Called when a entity is despawned
 * @phpstan-extends EntityEvent<Entity>
 */
class EntityDespawnEvent extends EntityEvent
{
	/** @var int */
	private $entityType;

	public function __construct(Entity $entity)
	{
		$this->entity = $entity;
		$this->entityType = $entity::NETWORK_ID;
	}

	/**
	 * @deprecated
	 */
	public function getType() : int
	{
		return $this->entityType;
	}

	/**
	 * @deprecated
	 */
	public function isCreature() : bool
	{
		return $this->entity instanceof Creature;
	}

	/**
	 * @deprecated
	 */
	public function isHuman() : bool
	{
		return $this->entity instanceof Human;
	}

	/**
	 * @deprecated
	 */
	public function isProjectile() : bool
	{
		return $this->entity instanceof Projectile;
	}

	/**
	 * @deprecated
	 */
	public function isVehicle() : bool
	{
		return $this->entity instanceof Vehicle;
	}

	/**
	 * @deprecated
	 */
	public function isItem() : bool
	{
		return $this->entity instanceof ItemEntity;
	}
}
