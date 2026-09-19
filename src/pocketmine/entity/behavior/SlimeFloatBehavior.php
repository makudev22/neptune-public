<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\hostile\Slime;

class SlimeFloatBehavior extends Behavior
{
	/** @var Slime */
	protected $mob;

	public function __construct(Slime $slime)
	{
		parent::__construct($slime);

		$this->setMutexBits(5);
		$this->mob->setSwimmer(true);
	}

	public function canStart() : bool
	{
		return $this->mob->isInsideOfWater() || $this->mob->isInsideOfLava();
	}

	public function onTick() : void
	{
		if ($this->random->nextFloat() < 0.8) {
			$this->mob->getJumpHelper()->setJumping(true);
		}

		$this->mob->getMoveHelper()->setSpeed(1.2);
	}
}
