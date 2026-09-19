<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Water;
use pocketmine\math\Vector3;
use pocketmine\Player;

class GlassBottle extends Item
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::GLASS_BOTTLE, $meta, "Glass Bottle");
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool
	{
		if ($blockClicked instanceof Water) {
			$waterPotion = ItemFactory::get(Item::POTION, 0, 1);
			$stack = clone $this;

			if ($player->hasFiniteResources() && $this->getCount() === 0) {
				$player->getInventory()->setItemInHand($waterPotion);
				return true;
			}

			$this->pop();

			foreach ($player->getInventory()->addItem($waterPotion) as $remains) {
				if (!$player->dropItem($remains)) {
					$player->getInventory()->setItemInHand($stack);
					return false;
				}
			}

			return true;
		}

		return false;
	}
}
