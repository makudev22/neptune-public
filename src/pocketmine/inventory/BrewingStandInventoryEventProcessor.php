<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\tile\BrewingStand;

class BrewingStandInventoryEventProcessor implements InventoryEventProcessor
{
	private BrewingStand $holder;
	private Level $level;

	public function __construct(BrewingStand $brewingStand, Level $level)
	{
		$this->holder = $brewingStand;
		$this->level = $level;
	}

	public function onSlotChange(Inventory $inventory, int $slot, Item $oldItem, Item $newItem) : ?Item
	{
		$this->level->scheduleDelayedBlockUpdate($this->holder, 1);
		return $newItem;
	}
}
