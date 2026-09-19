<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class PumpkinStem extends Stem {
	protected $id = self::PUMPKIN_STEM;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pumpkin Stem";
	}

	public function getSeed() : Item {
		return ItemFactory::get(ItemIds::PUMPKIN_SEEDS);
	}

	public function getCrop() : Item {
		return ItemFactory::get(ItemIds::PUMPKIN);
	}
}
