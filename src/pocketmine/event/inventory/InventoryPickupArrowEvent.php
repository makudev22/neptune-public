<?php


declare(strict_types=1);

namespace pocketmine\event\inventory;

use pocketmine\entity\projectile\Arrow;
use pocketmine\event\Cancellable;
use pocketmine\inventory\Inventory;

class InventoryPickupArrowEvent extends InventoryEvent implements Cancellable
{
	/** @var Arrow */
	private $arrow;

	public function __construct(Inventory $inventory, Arrow $arrow)
	{
		$this->arrow = $arrow;
		parent::__construct($inventory);
	}

	public function getArrow() : Arrow
	{
		return $this->arrow;
	}
}
