<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\hostile\Slime;

class SlimeRandomDirectionBehavior extends Behavior
{
	/** @var Slime */
	protected $mob;
	protected $randomYaw = 0;
	protected $directionTimer = 0;

	public function __construct(Slime $slime)
	{
		parent::__construct($slime);

		$this->setMutexBits(2);
	}

	public function canStart() : bool
	{
		return $this->mob->getTargetEntity() === null && ($this->mob->onGround || $this->mob->isInsideOfWater() || $this->mob->isInsideOfLava());
	}

	public function onTick() : void
	{
		if (--$this->directionTimer <= 0) {
			$this->directionTimer = 40 + $this->random->nextBoundedInt(60);
			$this->randomYaw = $this->random->nextBoundedInt(360);
		}

		$this->mob->getMoveHelper()->jumpWithYaw($this->randomYaw, false);
	}
}
