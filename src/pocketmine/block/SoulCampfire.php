<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\inventory\FurnaceType;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class SoulCampfire extends Campfire {
	protected $id = self::SOUL_CAMPFIRE;
	protected $itemId = ItemIds::SOUL_CAMPFIRE;

	public function getName() : string{
		return "Soul Campfire";
	}

	public function getLightLevel() : int{
		return $this->isExtinguished() ? 0 : 10;
	}

	public function getFurnaceType() : FurnaceType {
		return FurnaceType::SOUL_CAMPFIRE;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			ItemFactory::get(ItemIds::SOUL_SOIL)
		];
	}

	protected function getEntityCollisionDamage() : int{
		return 2;
	}
}
