<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityFallEvent extends EntityEvent implements Cancellable
{
	protected float $fallDistance;

	public function __construct(Entity $entity, float $fallDistance)
	{
		$this->entity = $entity;
		$this->fallDistance = $fallDistance;
	}

	public function getFallDistance() : float
	{
		return $this->fallDistance;
	}

	public function setFallDistance(float $fallDistance) : void
	{
		$this->fallDistance = $fallDistance;
	}
}
