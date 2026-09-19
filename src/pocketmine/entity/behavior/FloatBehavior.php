<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Mob;

class FloatBehavior extends Behavior
{
	private int $tick = 0;

	public function __construct(Mob $mob)
	{
		parent::__construct($mob);
		$this->mutexBits = 4;
	}

	public function canStart() : bool
	{
		return $this->mob->isInsideOfWater();
	}

	public function onStart() : void
	{
		$this->mob->setSwimmer(true);
	}

	public function onEnd() : void
	{
		$this->mob->setSwimmer(false);
	}

	public function onTick() : void
	{
		if ($this->random->nextFloat() < 0.8) {
			$this->mob->getJumpHelper()->setJumping(true);
		}
	}
}
