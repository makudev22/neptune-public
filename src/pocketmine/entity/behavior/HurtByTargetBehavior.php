<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Living;
use pocketmine\entity\Mob;

class HurtByTargetBehavior extends TargetBehavior
{
	protected $alertSameType;
	protected $hurtOwner;
	protected $revengeTimerOld = 0;

	public function __construct(Mob $mob, bool $alertSameType = false, bool $hurtOwner = false)
	{
		parent::__construct($mob, false);

		$this->alertSameType = $alertSameType;
		$this->hurtOwner = $hurtOwner;
	}

	public function canStart() : bool
	{
		$attacker = $this->mob->getRevengeTarget();
		$i = $this->mob->getRevengeTimer();

		return $i !== $this->revengeTimerOld && $attacker instanceof Living && !($this->hurtOwner && $this->mob->getOwningEntity() === $attacker) && $this->isSuitableTargetLocal($attacker, false);
	}

	public function onStart() : void
	{
		$this->mob->setTargetEntity($this->mob->getRevengeTarget());
		$this->revengeTimerOld = $this->mob->getRevengeTimer();

		if ($this->alertSameType) {
			$d = $this->getTargetDistance();

			foreach ($this->mob->level->getNearbyEntities($this->mob->getBoundingBox()->expandedCopy($d, 10, $d), $this->mob) as $entity) {
				if ($entity->getTargetEntity() === null) {
					$entity->setTargetEntity($this->mob->getRevengeTarget());
				}
			}
		}

		parent::onStart();
	}
}
