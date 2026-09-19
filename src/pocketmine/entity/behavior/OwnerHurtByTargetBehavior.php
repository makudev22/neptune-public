<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Tamable;

class OwnerHurtByTargetBehavior extends TargetBehavior
{
	/** @var Tamable */
	protected $mob;
	protected int $revengeTimerOld = 0;
	protected ?Entity $ownerAttacker = null;

	public function __construct(Tamable $mob)
	{
		parent::__construct($mob, false);

		$this->mutexBits = 1;
	}

	public function canStart() : bool
	{
		if ($this->mob->isTamed()) {
			$owner = $this->mob->getOwningEntity();

			if ($owner instanceof Living) {
				$this->ownerAttacker = $owner->getRevengeTarget();
				$i = $owner->getRevengeTimer();

				return $i !== $this->revengeTimerOld && $this->ownerAttacker instanceof Living && $this->isSuitableTargetLocal($this->ownerAttacker, false);
			}
		}

		return false;
	}

	public function onStart() : void
	{
		$this->mob->setTargetEntity($this->ownerAttacker);
		$owner = $this->mob->getOwningEntity();

		if ($owner instanceof Living) {
			$this->revengeTimerOld = $owner->getRevengeTimer();
		}

		parent::onStart();
	}
}
