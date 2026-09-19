<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class AzaleaLeaves extends Leaves
{
	protected $id = self::AZALEA_LEAVES;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Azalea Leaves";
	}

	public function getSaplingItem() : Item
	{
		return ItemFactory::get(ItemIds::AZALEA);
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
