<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\tile\Furnace;

class FurnaceInventoryEventProcessor implements InventoryEventProcessor
{
	private Furnace $furnace;
	private Level $level;

	public function __construct(Furnace $furnace, Level $level){
		$this->furnace = $furnace;
		$this->level = $level;
	}

	public function onSlotChange(Inventory $inventory, int $slot, Item $oldItem, Item $newItem) : ?Item{
		$this->level->scheduleDelayedBlockUpdate($this->furnace, 1);
		return $newItem;
	}
}
