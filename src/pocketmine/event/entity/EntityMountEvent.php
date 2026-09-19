<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityMountEvent extends EntityEvent implements Cancellable
{
	protected Entity $ridden;
	protected int $seatNumber;
	protected bool $causedByRider;

	public function __construct(Entity $entity, Entity $ridden, int $seatNumber = 0, bool $causedByRider = true)
	{
		$this->entity = $entity;
		$this->ridden = $ridden;
		$this->seatNumber = $seatNumber;
		$this->causedByRider = $causedByRider;
	}

	public function getRidden() : Entity
	{
		return $this->ridden;
	}

	public function getSeatNumber() : int
	{
		return $this->seatNumber;
	}

	public function getCausedByRider() : bool
	{
		return $this->causedByRider;
	}
}
