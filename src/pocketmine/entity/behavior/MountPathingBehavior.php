<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\passive\AbstractHorse;
use pocketmine\entity\utils\RandomPositionGenerator;
use pocketmine\network\mcpe\protocol\ActorEventPacket;

class MountPathingBehavior extends Behavior
{
	protected $rideTime = 0;
	/** @var AbstractHorse */
	protected $mob;

	public function __construct(AbstractHorse $mob)
	{
		parent::__construct($mob);

		$this->mutexBits = 7;
	}

	public function canStart() : bool
	{
		return $this->mob->getRiddenByEntity() !== null;
	}

	public function onTick() : void
	{
		if (!$this->mob->isTamed()) {
			if ($this->rideTime > 100 && !$this->mob->isRearing()) {
				if ($this->rideTime === 100) {
					$pos = RandomPositionGenerator::findRandomTargetBlock($this->mob, 10, 7);

					if ($pos !== null) {
						$this->mob->getNavigator()->tryMoveTo($pos, 1, $this->mob->distanceSquared($pos) + 2);
					}
				}

				if ($this->mob->random->nextBoundedInt(1) === 0) {
					$this->mob->setInLove(true);
					$this->mob->setTamed(true);
				} else {
					$this->mob->setRearing(true);
				}
			} elseif ($this->rideTime > 120) {
				$this->mob->broadcastEntityEvent(ActorEventPacket::TAME_FAIL);
				$this->mob->throwRider();
				$this->mob->setRearing(false);
			}

			$this->rideTime++;
			$this->mutexBits = 2;
		}
	}

	public function onEnd() : void
	{
		$this->rideTime = 0;

		$this->mob->setRearing(false);
		$this->mob->throwRider();
	}
}
