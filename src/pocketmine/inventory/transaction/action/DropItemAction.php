<?php


declare(strict_types=1);

namespace pocketmine\inventory\transaction\action;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;

/**
 * Represents an action involving dropping an item into the world.
 */
class DropItemAction extends InventoryAction
{
	public function __construct(Item $targetItem)
	{
		parent::__construct(ItemFactory::get(Item::AIR, 0, 0), $targetItem);
	}

	public function isValid(Player $source) : bool
	{
		return !$this->targetItem->isNull();
	}

	public function onPreExecute(Player $source) : bool
	{
		return $source->dropItem($this->targetItem);
	}

	public function execute(Player $source) : void
	{

	}

	public function revert(Player $source) : void
	{

	}
}
