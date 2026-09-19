<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Mob;
use pocketmine\entity\utils\RandomPositionGenerator;

class PanicBehavior extends RandomStrollBehavior
{
	public function __construct(Mob $mob, float $speedMultiplier = 1.0)
	{
		parent::__construct($mob, $speedMultiplier, 0);
	}

	public function canStart() : bool
	{
		if ($this->mob->getRevengeTarget() !== null || $this->mob->isOnFire()) {
			$this->targetPos = RandomPositionGenerator::findRandomTargetBlock($this->mob, 5, 4);

			if ($this->targetPos !== null) {
				return true;
			}
		}
		return false;
	}
}
