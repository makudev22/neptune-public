<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Tamable;

class StayWhileSittingBehavior extends Behavior
{
	/** @var Tamable */
	protected $mob;
	protected $isSitting = false;

	public function __construct(Tamable $mob)
	{
		parent::__construct($mob);
		$this->mutexBits = 1;
	}

	public function canStart() : bool
	{
		if ($this->mob->isTamed() && !$this->mob->isInsideOfWater() && !($this->mob->fallDistance > 0)) {
			$owner = $this->mob->getOwningEntity();

			return $owner === null ? true : ((($this->mob->distanceSquared($owner) < 144 && ($this->mob->getTargetEntity() !== null && $this->mob->getTargetEntity()->isAlive())) ? false : $this->isSitting));
		}

		return false;
	}

	public function onStart() : void
	{
		$this->mob->getNavigator()->clearPath(true);
		$this->mob->setSitting(true);
	}

	public function onEnd() : void
	{
		$this->mob->setSitting(false);
	}

	public function setSitting(bool $value) : void
	{
		$this->isSitting = $value;
	}
}
