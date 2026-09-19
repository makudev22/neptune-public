<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class MangroveLeaves extends Leaves
{
	protected $id = self::MANGROVE_LEAVES;

	public function getName() : string
	{
		return "Mangrove Leaves";
	}

	public function getSaplingItem() : Item
	{
		return ItemFactory::get(ItemIds::AIR);
	}

	public function canDropApples() : bool
	{
		return false;
	}

	public function getCheckDecayBitmask() : int{
		return 0x02;
	}

	public function getPersistentBitmask() : int{
		return 0x01;
	}
}
