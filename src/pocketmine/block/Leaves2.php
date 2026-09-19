<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class Leaves2 extends Leaves
{
	protected $id = self::LEAVES2;
	protected int $woodType = self::WOOD2;

	public function getName() : string
	{
		static $names = [
			self::ACACIA => "Acacia Leaves",
			self::DARK_OAK => "Dark Oak Leaves"
		];
		return $names[$this->getVariant()] ?? "Unknown";
	}

	public function getSaplingItem() : Item
	{
		return ItemFactory::get(Item::SAPLING, $this->getVariant() + 4);
	}

	public function canDropApples() : bool
	{
		return $this->getVariant() === self::DARK_OAK;
	}
}
