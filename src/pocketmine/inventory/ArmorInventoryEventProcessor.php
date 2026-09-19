<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\item\Item;

class ArmorInventoryEventProcessor implements InventoryEventProcessor
{
	/** @var Entity */
	private $entity;

	public function __construct(Entity $entity)
	{
		$this->entity = $entity;
	}

	public function onSlotChange(Inventory $inventory, int $slot, Item $oldItem, Item $newItem) : ?Item
	{
		$ev = new EntityArmorChangeEvent($this->entity, $oldItem, $newItem, $slot);
		$ev->call();
		if ($ev->isCancelled()) {
			return null;
		}

		return $ev->getNewItem();
	}
}
