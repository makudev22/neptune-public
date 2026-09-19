<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\hostile\Creeper;

class CreeperSwellBehavior extends Behavior
{
	/** @var Creeper */
	protected $mob;

	public function __construct(Creeper $mob)
	{
		parent::__construct($mob);
		$this->mutexBits = 1;
	}

	public function canStart() : bool
	{
		$target = $this->mob->getTargetEntity();
		return $target === null ? false : ($this->mob->isIgnited() || $this->mob->distance($target) < 3);
	}

	public function onTick() : void
	{
		$target = $this->mob->getTargetEntity();
		if ($this->mob->distance($target) > 7 || !$this->mob->canSeeEntity($target)) {
			$this->mob->setIgnited(false);
		} else {
			$this->mob->setIgnited(true);
		}
	}

	public function onEnd() : void
	{
		$this->mob->setTargetEntity(null);
	}
}
