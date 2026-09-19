<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\event\Cancellable;
use pocketmine\tile\BrewingStand;

/**
 * Called when a brewing stand consumes a new fuel item.
 */
class BrewingFuelUseEvent extends BlockEvent implements Cancellable
{
	private int $fuelTime = 20;

	public function __construct(
		private BrewingStand $brewingStand
	) {
		parent::__construct($brewingStand->getBlock());
	}

	public function getBrewingStand() : BrewingStand
	{
		return $this->brewingStand;
	}

	/**
	 * Returns how many times the fuel can be used for potion brewing before it runs out.
	 */
	public function getFuelTime() : int
	{
		return $this->fuelTime;
	}

	/**
	 * Sets how many times the fuel can be used for potion brewing before it runs out.
	 */
	public function setFuelTime(int $fuelTime) : void
	{
		if ($fuelTime <= 0) {
			throw new \InvalidArgumentException("Fuel time must be positive");
		}
		$this->fuelTime = $fuelTime;
	}
}
