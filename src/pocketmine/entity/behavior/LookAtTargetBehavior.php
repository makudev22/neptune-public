<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Entity;
use pocketmine\entity\Mob;

class LookAtTargetBehavior extends LookAtEntityBehavior
{
	public function __construct(Mob $mob, float $lookDistance = 8.0)
	{
		parent::__construct($mob, Entity::class, $lookDistance);
	}

	public function canStart() : bool
	{
		$target = $this->mob->getTargetEntity();

		if ($target !== null) {
			$this->nearestEntity = $target;

			return true;
		}

		return false;
	}
}
