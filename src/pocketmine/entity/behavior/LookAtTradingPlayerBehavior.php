<?php


declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\passive\Villager;

class LookAtTradingPlayerBehavior extends LookAtPlayerBehavior
{
	/** @var Villager */
	protected $mob;

	public function __construct(Villager $villager)
	{
		parent::__construct($villager, 8);
	}

	public function canStart() : bool
	{
		if ($this->mob->getTradingPlayer() !== null) {
			$this->nearestEntity = $this->mob->getTradingPlayer();

			return true;
		}

		return false;
	}

}
