<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Living;

class OwnerHurtTargetBehavior extends Behavior
{
	protected $mutexBits = 1;

	public function canStart() : bool
	{
		$owner = $this->mob->getOwningEntity();
		if ($owner instanceof Living) {
			$this->mob->setTargetEntity($owner->getLastAttackedEntity());
			return true;
		}

		return false;
	}

	public function canContinue() : bool
	{
		return false;
	}
}
