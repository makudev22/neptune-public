<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\level\Level;

/**
 * @phpstan-extends EntityEvent<Entity>
 */
class EntityLevelChangeEvent extends EntityEvent implements Cancellable
{
	/** @var Level */
	private $originLevel;
	/** @var Level */
	private $targetLevel;

	public function __construct(Entity $entity, Level $originLevel, Level $targetLevel)
	{
		$this->entity = $entity;
		$this->originLevel = $originLevel;
		$this->targetLevel = $targetLevel;
	}

	public function getOrigin() : Level
	{
		return $this->originLevel;
	}

	public function getTarget() : Level
	{
		return $this->targetLevel;
	}
}
