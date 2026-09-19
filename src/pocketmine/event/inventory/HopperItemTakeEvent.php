<?php


declare(strict_types=1);

namespace pocketmine\event\inventory;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\Cancellable;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;

class HopperItemTakeEvent extends InventoryEvent implements Cancellable {
	public const int EVENT_TAKE_ITEM_FROM_INVENTORY = 0;
	public const int EVENT_TAKE_ITEM = 1;

	public function __construct(private ItemEntity|Item $item, Inventory $inventory, private int $type = self::EVENT_TAKE_ITEM, private ?Inventory $inventoryFrom = null)
	{
		$this->inventory = $inventory;
	}

	public function getInventoryFrom() : ?Inventory
	{
		return $this->inventoryFrom;
	}

	public function getType() : int
	{
		return $this->type;
	}

	public function getItem() : ItemEntity|Item
	{
		return $this->item;
	}
}
