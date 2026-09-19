<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityDismountEvent extends EntityEvent implements Cancellable
{
	protected Entity $ridden;
	protected bool $immediate;

	public function __construct(Entity $entity, Entity $ridden, bool $immediate = false)
	{
		$this->entity = $entity;
		$this->ridden = $ridden;
		$this->immediate = $immediate;
	}

	public function getRidden() : Entity
	{
		return $this->ridden;
	}

	public function getImmediate() : bool
	{
		return $this->immediate;
	}
}
