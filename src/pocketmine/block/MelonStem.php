<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class MelonStem extends Stem {
	protected $id = self::MELON_STEM;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Melon Stem";
	}

	public function getSeed() : Item {
		return ItemFactory::get(ItemIds::MELON_SEEDS);
	}

	public function getCrop() : Item {
		return ItemFactory::get(ItemIds::MELON_BLOCK);
	}
}
