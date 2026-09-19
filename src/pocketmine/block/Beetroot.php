<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use pocketmine\item\ItemIds;
use pocketmine\utils\Random;

class Beetroot extends Crops {
	protected $id = self::BEETROOT_BLOCK;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Beetroot Block";
	}

	public function getMaxAge() : int{
		return 3;
	}

	public function onRandomTick() : void{
		if ($this->level->random->nextBoundedInt(3) !== 0) {
			parent::onRandomTick();
		}
	}

	protected function getBonemealAgeIncrease(Random $random) : int{
		return (int) (parent::getBonemealAgeIncrease($random) / 3);
	}

	public function getSeed() : Item {
		return ItemFactory::get(ItemIds::BEETROOT_SEEDS);
	}

	public function getCrop() : Item {
		return ItemFactory::get(ItemIds::BEETROOT);
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		if ($this->isMaxAge()) {
			return [
				ItemFactory::get(ItemIds::BEETROOT),
				ItemFactory::get(ItemIds::BEETROOT_SEEDS, 0, FortuneDropHelper::binomial($item, 0))
			];
		} else {
			return [
				ItemFactory::get(ItemIds::BEETROOT_SEEDS)
			];
		}
	}
}
