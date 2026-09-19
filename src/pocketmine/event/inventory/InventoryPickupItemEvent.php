<?php


declare(strict_types=1);

namespace pocketmine\event\inventory;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\Cancellable;
use pocketmine\inventory\Inventory;

class InventoryPickupItemEvent extends InventoryEvent implements Cancellable
{
	/** @var ItemEntity */
	private $item;

	public function __construct(Inventory $inventory, ItemEntity $item)
	{
		$this->item = $item;
		parent::__construct($inventory);
	}

	public function getItem() : ItemEntity
	{
		return $this->item;
	}
}
