<?php


declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\projectile\Projectile;
use pocketmine\event\Cancellable;

/**
 * @phpstan-extends EntityEvent<Projectile>
 */
class ProjectileLaunchEvent extends EntityEvent implements Cancellable
{
	public function __construct(Projectile $entity)
	{
		$this->entity = $entity;
	}

	public function getEntity() : Projectile
	{
		return $this->entity;
	}
}
