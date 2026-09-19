<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Mob;

use function sqrt;

class LeapAtTargetBehavior extends Behavior
{
	/** @var float */
	protected $leapHeight;
	protected $mustBeOnGround;
	protected $leapTarget;

	public function __construct(Mob $mob, float $leapHeight, bool $mustBeOnGround = true)
	{
		parent::__construct($mob);

		$this->leapHeight = $leapHeight;
		$this->mustBeOnGround = $mustBeOnGround;

		$this->mutexBits = 5;
	}

	public function canStart() : bool
	{
		$this->leapTarget = $this->mob->getTargetEntity();

		if ($this->leapTarget == null) {
			return false;
		}

		$distance = $this->mob->distance($this->leapTarget);

		return $distance >= 4 && $distance <= 16 && ($this->mustBeOnGround ? $this->mob->isOnGround() : true) && $this->random->nextBoundedInt(5) == 0;
	}

	public function canContinue() : bool
	{
		return !$this->mob->onGround;
	}

	public function onStart() : void
	{
		$d1 = $this->leapTarget->x - $this->mob->x;
		$d2 = $this->leapTarget->z - $this->mob->z;
		$f = sqrt($d1 ** 2 + $d2 ** 2);

		$motion = $this->mob->getMotion();

		$motion->x += $d1 / $f * 0.5 * 0.8 + $motion->x * 0.2;
		$motion->y = $this->leapHeight;
		$motion->z += $d2 / $f * 0.5 * 0.8 + $motion->z * 0.2;

		$this->mob->setMotion($motion);
	}
}
