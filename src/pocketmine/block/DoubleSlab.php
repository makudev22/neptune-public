<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

abstract class DoubleSlab extends Solid
{
	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	abstract public function getSlabId() : int;

	public function getName() : string
	{
		return "Double " . BlockFactory::get($this->getSlabId(), $this->getVariant())->getName();
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get($this->getSlabId(), $this->getVariant(), 2)
		];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return false;
	}

	public function getPickedItem(bool $addUserData = false) : Item
	{
		return ItemFactory::get($this->getSlabId(), $this->getVariant());
	}
}
